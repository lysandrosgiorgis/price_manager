<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Competition\Company;
use App\Models\Competition\Product as CompetitionProduct;
use App\Helpers\ExcelHelper;

class ProductController extends Controller
{
    private $filters = [
        'name' => '',
        'sku' => '',
        'mpn' => '',
        'barcode' => '',
        'status' => '',
        'sync' => '',
        'created_at' => '',
        'updated_at' => '',
        'sort' => '',
        'order' => '',
    ];

    public function productList(Request $request){
        $data['page_title'] = __('Products');

        $data['list'] = $this->getList($request);
        return view('pages.list', $data);
    }

    public function create(){
        $data['form'] = $this->getForm();

        return view('pages.form', $data);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' 		=> 'required',
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);
        $product = new Product();
        $product->name 		    = $request->name;
        $product->description 	= $request->description;
        $product->mpn 	        = $request->mpn;
        $product->barcode 	    = $request->barcode;
        $product->sku 	        = $request->sku;
        $product->image 	    = $request->image;
        $product->starting_price 	    = $request->starting_price;
        $product->final_price 	    = $request->final_price;
        $product->status 	    = $request->status;
        $product->sync 	        = $request->sync;
        $product->save();


        if($request->saveAndEdit == 1){
            return redirect( route('catalog.product.update', $product->id) )->with('success','Product updated successfully');
        }else{
            return redirect( route('catalog.product') )->with('success','Product updated successfully');
        }
    }

    public function edit($id){
        $product = Product::findOrFail($id);


        $data['form'] = $this->getForm($product);

        return view('pages.form', $data);
    }

    public function update($id, Request $request){
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' 		=> 'required',
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);

        $product->name 		    = $request->name;
        $product->description 	= $request->description;
        $product->image 	    = $request->image;
        $product->mpn 	        = $request->mpn;
        $product->barcode 	    = $request->barcode;
        $product->sku 	        = $request->sku;
        $product->status 	    = $request->status;
        $product->starting_price 	    = $request->starting_price;
        $product->final_price 	    = $request->final_price;
        $product->sync 	        = $request->sync;
        $product->save();


        if($request->saveAndEdit == 1){
            return redirect( route('catalog.product.update', $product->id) )->with('success','Product updated successfully');
        }else{
            return redirect( route('catalog.product') )->with('success','Product updated successfully');
        }
    }

    public function getForm(Product $product = null){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        if($product){
            $data['pageTitle'] = __('Edit :productName', ['productName' => $product->name]);
            $data['form']['action'] = route('catalog.product.update', $product->id, $url);
            $data['form']['id'] = 'catalogProductUpdate';
        }else{
            $data['pageTitle'] = __('Create product');
            $data['form']['action'] = route('catalog.product.create');
            $data['form']['id'] = 'catalogProductCreate';
        }

        $data['form']['buttons']['bottom']['back'] = [
            'type'  => 'link',
            'id'  => $data['form']['id'].'BackBtn',
            'href'  => route('catalog.product', $url),
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
            'name'          => '',
            'description'   => '',
            'image'         => '',
            'mpn'         => '',
            'sku'         => '',
            'barcode'         => '',
            'final_price'         => '',
            'starting_price'         => '',
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
                                'value' 	=> old('name', $product ? $product->name : $defaults['name'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'description',
                                'label' 	=> __('Description'),
                                'type' 		=> 'textarea',
                                'wide' 		=> 1,
                                'value' 	=> old('description', $product ? $product->description : $defaults['description'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'starting_price',
                                'label' 	=> __('Price'),
                                'type' 		=> 'text',
                                'value' 	=> old('starting_price', $product ? $product->starting_price : $defaults['starting_price'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'final_price',
                                'label' 	=> __('Final price'),
                                'type' 		=> 'text',
                                'value' 	=> old('final_price', $product ? $product->final_price : $defaults['final_price'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'image',
                                'label' 	=> __('Image'),
                                'wide' 		=> 1,
                                'type' 		=> 'image',
                                'value' 	=> old('image', $product ? $product->image : $defaults['image'] ),
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
                                'value' 	=> old('status', $product ? $product->status : $defaults['status'] ),
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
                                'value' 	=> old('sync', $product ? $product->sync : $defaults['sync'] ),
                                'error'     => '',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' 	=> __('Info'),
                'fieldsets' => [
                    [
                        'legend'    => false,
                        'fields' 	=> [
                            [
                                'name' 		=> 'mpn',
                                'label' 	=> __('MPN'),
                                'type' 		=> 'text',
                                'value' 	=> old('mpn', $product ? $product->mpn : $defaults['mpn'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'barcode',
                                'label' 	=> __('Barcode'),
                                'type' 		=> 'text',
                                'value' 	=> old('barcode', $product ? $product->barcode : $defaults['barcode'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'sku',
                                'label' 	=> __('SKU'),
                                'type' 		=> 'text',
                                'value' 	=> old('sku', $product ? $product->sku : $defaults['sku'] ),
                                'error'     => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $data;
    }

    public function getList(Request $request, $data = []){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        $data['list']['title'] = __('Products');

        $data['list']['buttons']['top'][] = [
            'class' => 'btn btn-info text-white',
            'action' => 'showImportProducts()',
            'type'  => 'button',
            'label' => __('Import products'),
            'icon' => 'fa fa-file-excel',
        ];
        $data['list']['beforeBody'][] = 'templates.components.productsImportModal';
        $data['list']['importProducts']['companies'] = Company::where('status','=',1)->get();
        $data['list']['buttons']['top'][] = [
            'class' => 'btn btn-success ',
            'type'  => 'link',
            'href'  => route('catalog.product.create'),
            'label' => __('Create product'),
            'icon' => 'fa fa-plus',
        ];
        $data['list']['columns'] = [
            'name'      => [
                'label' => __('Name'),
                'class' => 'align-middle',
            ],
            'starting_price'      => [
                'label' => __('Price'),
                'class' => 'align-middle',
            ],
            'final_price'      => [
                'label' => __('Final price'),
                'class' => 'align-middle',
            ],
            'mpn'      => [
                'label' => __('MPN'),
                'class' => 'align-middle',
            ],
            'sku'      => [
                'label' => __('SKU'),
                'class' => 'align-middle',
            ],
            'model'      => [
                'label' => __('Model'),
                'class' => 'align-middle',
            ],
            'model_02'      => [
                'label' => __('Model 2'),
                'class' => 'align-middle',
            ],
            'barcode'      => [
                'label' => __('Barcode'),
                'class' => 'align-middle',
            ],
            'status'    => [
                'label' => __('Status'),
                'class' => 'text-center align-middle',
                'width' => '10'
            ],
            'sync'      => [
                'label' => __('Sync'),
                'class' => 'text-center align-middle',
                'width' => '10'
            ],
        ];
        $data['list']['listActions'] = [
            'width' => '104',
        ];
        $data['list']['list_items'] = [];
        $products = Product::paginate($request->input('limit', 15));
        $data['list']['pagination'] = $products;
        foreach ($products as $product){
            $list_item = [
                'name'      => $product->name,
                'sku'      => $product->sku,
                'mpn'      => $product->mpn,
                'model'      => $product->model,
                'model_02'      => $product->model_02,
                'starting_price'      => $product->starting_price,
                'final_price'      => $product->final_price,
                'barcode'      => $product->barcode,
                'status'    => view('templates.column.icon', [
                    'icon' => ($product->status == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($product->status == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
                'sync'    => view('templates.column.icon', [
                    'icon' => ($product->sync == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($product->sync == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
            ];
            $list_item['actions']['update'] = [
                'class' => 'btn btn-info text-white',
                'type'  => 'link',
                'href'  => route('catalog.product.update', $product->id),
                'label' => __('Update product'),
                'hideLabel' => 1,
                'tooltip' => 1,
                'icon' => 'fa fa-pencil',
            ];
            $list_item['actions']['delete'] = [
                'class' => 'btn btn-danger',
                'type'  => 'button',
                'action'  => "if(confirm('".__('Are you sure you want to delete?')."')){ window.location = '".route('catalog.product.delete', $product->id)."';}",
                'label' => __('Delete product'),
                'hideLabel' => 1,
                'tooltip' => 1,
                'icon' => 'fa fa-trash',
            ];
            $data['list']['list_items'][] = $list_item;
        }

        return $data['list'];
    }

    public function delete($id, Request $request){
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect( route('catalog.product') )->with('success','Product updated successfully');
    }

    public function importProducts(Request $request){
        $productsIndex = [];
        foreach(Product::all() as $product){
            $productsIndex[$product->sku] = $product->id;
        }
        $companies = $request->company;
        $excelHelper = new ExcelHelper;
        $response = $excelHelper->excelFileToArray($request->file('import'), 2, 0);
        $products = [];
        foreach($response[0] as $row){
            if(isset($productsIndex[$row[0]])) continue;
            $newProduct = new Product();
            $newProduct->name = $row[1];
            $newProduct->sku = $row[0];
            $newProduct->model = $row[2];
            $newProduct->model_2 = $row[3];
            $newProduct->mpn = $row[16];
            $newProduct->save();

            foreach($request->company as $company_id){
                $competitionProduct = new CompetitionProduct();
                $competitionProduct->product_id = $newProduct->id;
                $competitionProduct->company_id = $company_id;
                $competitionProduct->save();
            }
            $products[] = $row[0];
        }
    }
}
