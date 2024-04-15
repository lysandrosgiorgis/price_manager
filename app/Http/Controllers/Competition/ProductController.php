<?php

namespace App\Http\Controllers\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Competition\Product as CompanyProduct;
use App\Models\Competition\ProductPrice as CompanyProductPrice;

class ProductController extends Controller
{
    private $filters = [
        'product_id' => '',
        'name' => '',
        'sku' => '',
        'mpn' => '',
        'barcode' => '',
        'brand' => '',
        'status' => '',
        'sync' => '',
        'created_at' => '',
        'updated_at' => '',
        'sort' => '',
        'order' => '',
    ];

    public function productList(Request $request){
        $data['page_title'] = __('Competition Products');

        $data['list'] = $this->getList($request);
        return view('pages.list', $data);
    }

    public function getList(Request $request, $data = []){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }
        $data['product_filters'] = $request->all();


        $data['list']['title'] = __('Products');

        $data['list']['buttons']['top'][] = [
            'class' => 'btn btn-success ',
            'type'  => 'link',
            'href'  => route('competition.product.create'),
            'label' => __('Create product'),
            'icon' => 'fa fa-plus',
        ];
        $data['list']['columns'] = [
            'img'      => [
                'label' => __('Εικόνα'),
                'class' => 'align-middle',
                'width' => 160,
            ],
            'product_id'      => [
                'label' => __('Κεντρικό προϊόν'),
                'class' => 'align-middle',
                'hideLabel' => 1,
            ],
            'name'      => [
                'label' => __('Όνομα'),
                'class' => 'align-middle',
            ],
            'chart'      => [
                'label' => __('Chart'),
                'class' => 'align-middle',
            ],
            'barcode'      => [
                'label' => __('Barcode'),
                'class' => 'align-middle',
            ],
            'position'      => [
                'label' => __('Θέση'),
                'class' => 'align-middle',
            ],
            'starting_price'      => [
                'label'     => __('Price'),
                'class'     => 'align-middle',
            ],
            'final_price'      => [
                'label'    => __('Final price'),
                'class'    => 'align-middle',
            ],
            'updated_at'      => [
                'label' => __('Τελευταία Αλλαγή'),
                'class' => 'align-middle',
            ],
        ];
        $data['list']['listActions'] = [
            'width' => '104',
        ];
        $data['list']['list_items'] = [];
        $products = CompanyProduct::filter($data['product_filters'])->paginate($request->input('limit', 15));
        $data['list']['pagination'] = $products;
        $data['list']['beforeBody'][] = 'templates.components.productMatchModal';
        foreach ($products as $product){
            $companyProductPrice = CompanyProductPrice::where('product_id', $product->id)->orderBy('date','desc')->first();

            $list_item = [
                'img'                    => view('templates.column.img', [
                    'img' => ($product->image) ? asset('storage/'.$product->image) : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                ]),
                'product_id'             => view('templates.column.icon', [
                    'icon'      => ($product->product_id) ? 'fa fa-link' : 'fa fa-link-slash',
                    'hideLabel' => 1,
                    'label'     => ($product->product_id) ? $product->product->name : 'Αντιστοίχιση',
                    'tooltip'   =>1,
                    'action'    => ($product->product_id) ? 'onclick="location=\''.route('catalog.product.info',$product->product_id).'\'"' : 'onclick="findProductMatch('.$product->id.')"',
                ]),
                'name'                   => $product->name,
                'model'                  => $product->model,
                'sku'                    => $product->sku,
                'mpn'                    => $product->mpn,
                'barcode'                => $product->barcode,
                'brand'                  => $product->brand,
                'updated_at'             => $product->updated_at,
                'chart'                  => view('templates.column.chart', [
                    'id' => 'prices-'.$product->id,
                    'type' => 'spline',
                    'title' => [
                        'text' => '',
                    ],
                    'xAxis' => [
                        'type' => 'datetime',
                        'dateTimeLabelFormats' => [
                            'millisecond' => '%d-%m',
                            'second' => '%d-%m',
                            'minute' => '%d-%m',
                            'hour' => '%d-%m',
                            'day' => '%m-%Y',
                            'month' => '%m-%Y',
                            'year' => '%m-%Y',
                        ],
//                        'visible' => false,
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => ''
                        ],
                    ],
                    'tooltip' => [
                        'crosshairs' => true,
                        'shared' => true,
                        'valueSuffix' => '€',
                        'xDateFormat' => '%d-%m-%Y'
                    ],
                    'legend' => [
                        'enabled' => false,
                    ],
                    'plotOptions' => [
                        'series' => [
                            'lineWidth' => 1,
                            'marker' => [
//                                'enabled' => false,
                                'states' => [
                                    'hover' => [
                                        'enabled' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'series' => [
                        [
                            'name' => 'Τιμή',
                            'data' => CompanyProductPrice::where('product_id', $product->id)->orderBy('date','asc')->get()->map(function ($productPrice) {
                                return [strtotime($productPrice->date) * 1000, 1 * $productPrice->final_price , $productPrice->id];
                            })->toArray(),
                        ],
                    ],
                ]),
                'position'               => $product->position,
                'starting_price'         => ($companyProductPrice->price != $companyProductPrice->final_price) ? $companyProductPrice->price : '',
                'final_price'            => $companyProductPrice->final_price,
                'status'                 => view('templates.column.icon', [
                    'icon' => ($product->status == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($product->status == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
                'sync'                   => view('templates.column.icon', [
                    'icon' => ($product->sync == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($product->sync == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
            ];
            $list_item['actions']['info'] = [
                'class'     => 'btn btn-success text-white btn-sm',
                'type'      => 'link',
                'href'      => route('competition.product.info', $product->id),
                'label'     => __('Product info'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-eye',
            ];
            $list_item['actions']['update'] = [
                'class'     => 'btn btn-info text-white btn-sm',
                'type'      => 'link',
                'href'      => route('competition.product.update', $product->id),
                'label'     => __('Update product'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-pencil',
            ];
            $list_item['actions']['delete'] = [
                'class'     => 'btn btn-danger btn-sm',
                'type'      => 'button',
                'action'    => "if(confirm('".__('Are you sure you want to delete?')."')){ window.location = '".route('competition.product.delete', $product->id)."';}",
                'label'     => __('Delete product'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-trash',
            ];
            $data['list']['list_items'][] = $list_item;
        }
        $data['list']['visibleColumns'] = [
            'img',
            'name',
            'chart',
            'barcode',
            'position',
            'starting_price',
            'final_price',
        ];
        $data['list']['filters_form'] = 'competition.product';
        $data['list']['filters'] = [
            [
                'name'  => 'name',
                'type'  => 'text',
                'value' => $request->input('name', ''),
                'label' => __('Όνομα'),
            ],
            [
                'name'  => 'barcode',
                'type'  => 'text',
                'value' => $request->input('barcode', ''),
                'label' => __('Barcode'),
            ],
            [
                'name'  => 'position',
                'type'  => 'text',
                'value' => $request->input('position', ''),
                'label' => __('Θέση'),
            ],
        ];

        return $data['list'];
    }

    public function edit($id){
        $companyProduct = CompanyProduct::findOrFail($id);

        $data['form'] = $this->getForm($companyProduct);

        return view('pages.form', $data);
    }

    public function update($id, Request $request){
        $companyProduct = CompanyProduct::findOrFail($id);

        $validatedData = $request->validate([
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);

        $companyProduct->product_id 	= $request->product_id;
        $companyProduct->company_id 	= $request->company_id;
        $companyProduct->mpn 	        = $request->mpn;
        $companyProduct->sku 	        = $request->sku;
        $companyProduct->barcode 	    = $request->barcode;
        $companyProduct->brand 	        = $request->brand;
        $companyProduct->url 	        = $request->url;
        $companyProduct->image 	        = $request->image;
        $companyProduct->status 	    = $request->status;
        $companyProduct->sync 	        = $request->sync;
        $companyProduct->save();


        if($request->saveAndEdit == 1){
            return redirect( route('competition.product.update', $companyProduct->id) )->with('success','Product updated successfully');
        }else{
            return redirect( route('competition.product') )->with('success','Company updated successfully');
        }
    }

    public function getForm(CompanyProduct $companyProduct = null){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        if($companyProduct){
            $data['pageTitle'] = __('Edit :Name', ['Name' => $companyProduct->name]);
            $data['form']['action'] = route('competition.product.update', $companyProduct->id);
            $data['form']['id'] = 'catalogCompanyUpdate';
        }else{
            $data['pageTitle'] = __('Create company');
            $data['form']['action'] = route('competition.product.create');
            $data['form']['id'] = 'catalogCompanyCreate';
        }

        $data['form']['buttons']['bottom']['back'] = [
            'type'  => 'link',
            'id'  => $data['form']['id'].'BackBtn',
            'href'  => route('competition.product'),
            'class' => 'btn-secondary',
            'label' => __('Back'),
            'icon'  => 'fa fa-reply',
        ];
        $data['form']['buttons']['bottom']['save'] = [
            'type'  => 'button',
            'id'  => $data['form']['id'].'SaveBtn',
            'action'=> "document.getElementById('".$data['form']['id']."').submit();",
            'class' => 'btn-primary',
            'label' => __('Save'),
            'icon'  => 'fa fa-save',
        ];
        $data['form']['buttons']['bottom']['saveAndEdit'] = [
            'type'  => 'button',
            'id'  => $data['form']['id'].'SaveAndEditBtn',
            'action'=> "document.querySelector('#".$data['form']['id']." #saveAndEdit').value = 1;document.getElementById('".$data['form']['id']."').submit();",
            'class' => 'btn-outline-primary',
            'label' => __('Save & edit'),
            'icon'  => 'fa fa-save',
        ];

        $defaults = [
            'product_id'    => '',
            'company_id'    => '',
            'name'          => '',
            'description'   => '',
            'model'         => '',
            'mpn'           => '',
            'sku'           => '',
            'barcode'       => '',
            'brand'         => '',
            'url'           => '',
            'image'         => '',
            'status'        => 'active',
            'sync'          => 'active',
            'created_at'    => '',
            'updated_at'    => '',
        ];

        $data['form']['tabs'] = [
            [
                'label' 	=> __('General'),
                'active' 	=> 1,
                'fieldsets' => [
                    [
                        'legend'    => false,
                        'fields' 	=> [
                            [
                                'name' 		=> 'name',
                                'label' 	=> __('Name'),
                                'type' 		=> 'text',
                                'wide' 		=> 1,
                                'disabled' 	=> 1,
                                'value' 	=> old('name', $companyProduct ? $companyProduct->name : $defaults['name'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'url',
                                'label' 	=> __('URL'),
                                'type' 		=> 'text',
                                'wide' 		=> 1,
                                'value' 	=> old('url', $companyProduct ? $companyProduct->url : $defaults['url'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'image',
                                'label' 	=> __('Image'),
                                'type' 		=> 'image',
                                'wide' 		=> 1,
                                'value' 	=> old('image', $companyProduct ? asset('storage/'.$companyProduct->image) : $defaults['image'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		            => 'product_id',
                                'label' 	            => __('Main Product'),
                                'autocomplete_name'     => 'product',
                                'autocomplete_value'    => old('product', $companyProduct && $companyProduct->product_id ? $companyProduct->product->name : '' ),
                                'type' 		            => 'autocomplete',
                                'source'                => route('catalog.product.autocomplete'),
                                'value' 	            => old('product_id', $companyProduct ? $companyProduct->product_id : $defaults['product_id'] ),
                                'error'                 => '',
                            ],
                            [
                                'name' 		            => 'company_id',
                                'label' 	            => __('Company'),
                                'autocomplete_name'     => 'company',
                                'autocomplete_value'    => old('company', $companyProduct && $companyProduct->company_id ? $companyProduct->company->name : '' ),
                                'type' 		            => 'autocomplete',
                                'source'                => route('competition.company.autocomplete'),
                                'value' 	            => old('company_id', $companyProduct ? $companyProduct->company_id : $defaults['company_id'] ),
                                'error'                 => '',
                            ],
                            [
                                'name' 		=> 'description',
                                'label' 	=> __('Description'),
                                'type' 		=> 'textarea',
                                'wide' 		=> 1,
                                'value' 	=> old('url', $companyProduct ? $companyProduct->description : $defaults['description'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'status',
                                'label' 	=> __('Status'),
                                'type' 		=> 'select',
                                'options'   => [
                                    [
                                        'label' => __('Active'),
                                        'value' => 'active',
                                    ],
                                    [
                                        'label' => __('Inactive'),
                                        'value' => 'inactive',
                                    ],
                                ],
                                'value' 	=> old('status', $companyProduct ? $companyProduct->status : $defaults['status'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'sync',
                                'label' 	=> __('Sync'),
                                'type' 		=> 'select',
                                'options'   => [
                                    [
                                        'label' => __('Active'),
                                        'value' => 'active',
                                    ],
                                    [
                                        'label' => __('Inactive'),
                                        'value' => 'inactive',
                                    ],
                                ],
                                'value' 	=> old('sync', $companyProduct ? $companyProduct->sync : $defaults['sync'] ),
                                'error'     => '',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' 	=> __('Data'),
                'fieldsets' => [
                    [
                        'legend'    => false,
                        'fields' 	=> [
                                [
                                    'name' 		=> 'model',
                                    'label' 	=> __('Model'),
                                    'type' 		=> 'text',
                                    'value' 	=> old('model', $companyProduct ? $companyProduct->model : $defaults['model'] ),
                                    'error'     => '',
                                ],
                                [
                                    'name' 		=> 'sku',
                                    'label' 	=> __('SKU'),
                                    'type' 		=> 'text',
                                    'value' 	=> old('sku', $companyProduct ? $companyProduct->sku : $defaults['sku'] ),
                                    'error'     => '',
                                ],
                                [
                                    'name' 		=> 'mpn',
                                    'label' 	=> __('MPN'),
                                    'type' 		=> 'text',
                                    'value' 	=> old('mpn', $companyProduct ? $companyProduct->mpn : $defaults['mpn'] ),
                                    'error'     => '',
                                ],
                                [
                                    'name' 		=> 'barcode',
                                    'label' 	=> __('Barcode'),
                                    'type' 		=> 'text',
                                    'value' 	=> old('barcode', $companyProduct ? $companyProduct->barcode : $defaults['barcode'] ),
                                    'error'     => '',
                                ],
                                [
                                    'name' 		=> 'created_at',
                                    'label' 	=> __('Created At'),
                                    'type' 		=> 'text',
                                    'disabled' 	=> 1,
                                    'value' 	=> old('created_at', $companyProduct ? $companyProduct->created_at : $defaults['created_at'] ),
                                    'error'     => '',
                                ],
                                [
                                    'name' 		=> 'updated_at',
                                    'label' 	=> __('Updated At'),
                                    'type' 		=> 'text',
                                    'disabled' 	=> 1,
                                    'value' 	=> old('updated_at', $companyProduct ? $companyProduct->updated_at : $defaults['updated_at'] ),
                                    'error'     => '',
                                ],
                            ],
                    ],
                ],
            ],
        ];

        return $data;
    }

    public function delete($id, Request $request){
        $company_product = CompanyProduct::findOrFail($id);
        $company_product->delete();
        $company_product = CompanyProductPrice::where('product_id','=', $id)->delete();
        return redirect( route('competition.product') )->with('success','Product updated successfully');
    }
}
