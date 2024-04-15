<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductPrice;
use App\Models\Competition\Company;
use App\Models\Competition\Product as CompanyProduct;
use App\Helpers\ExcelHelper;
use Illuminate\Support\Facades\DB;

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

    public function info($id){
        $product      = Product::findOrFail($id);
        $competitotrs = CompanyProduct::leftJoin('companies', 'company_products.company_id', '=', 'companies.id')
            ->select([
                'company_products.company_id',
                'company_products.url',
                'company_products.description',
                'company_products.updated_at',
                'companies.name',
            ])
            ->where('product_id','=',$id)->get();
        $product_competitors = [];
        foreach($competitotrs as $competitor) {
            $product_competitors[$competitor->company_id] = [
                'company_id'  => $competitor->company_id,
                'name'        => $competitor->name,
                'url'         => $competitor->url,
                'description' => $competitor->description,
                'updated_at'  => date('Y-m-d',strtotime($competitor->updated_at)),
            ];
        }
        $data = [
            'id'             =>  $product->id,
            'name'           =>  $product->name,
            'image'            => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
            'starting_price' =>  $product->starting_price,
            'final_price'    =>  $product->final_price,
            'final_from'     =>  $product->final_from,
            'final_to'       =>  $product->final_to,
            'lowest_price'   =>  $product->lowest_price,
            'highest_price'  =>  $product->highest_price,
            'chart'                  => view('templates.column.chart', [
                'id' => 'prices-'.$product->id,
                'type' => 'arearange',
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
                        'day' => '%d-%m-%Y',
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
                        'type'  => 'spline',
                        'data' => ProductPrice::where('product_id', $product->id)->orderBy('date','asc')->get()->map(function ($productPrice) {
                            return [strtotime($productPrice->date) * 1000, 1 * $productPrice->final_price , $productPrice->id];
                        })->toArray(),
                    ],
                ],
            ]),
            'competitors'   => $product_competitors
        ];

        return view('pages.product', $data);
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
        $data['product_filters'] = $request->all();


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
            'img'      => [
                'label' => __('Εικόνα'),
                'class' => 'align-middle',
                'width' => 220,
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
            'updated_at'      => [
                'label' => __('Τελευταία Αλλαγή'),
                'class' => 'align-middle',
            ],
            'cheapest'      => [
                'label' => __('Φθηνότερος'),
                'class' => 'align-middle',
            ],
            'cheapest_days'      => [
                'label' => __('Μέρες'),
                'class' => 'align-middle',
            ],
            'priciest'      => [
                'label' => __('Ακριβότερος'),
                'class' => 'align-middle',
            ],
            'competitors_no'      => [
                'label' => __('No. Ανταγωνιστών'),
                'class' => 'align-middle',
            ],
            'lowest_possible_price'      => [
                'label' => __('ΧΔΤ'),
                'class' => 'align-middle',
            ],
            'highest_possible_price'      => [
                'label' => __('ΥΔΤ'),
                'class' => 'align-middle',
            ],
            'entersoft_update'      => [
                'label' => __('Entersoft Update'),
                'class' => 'align-middle',
            ],
            'entersoft_offer'      => [
                'label' => __('Προτ. τιμή'),
                'class' => 'align-middle',
            ],
            'value'      => [
                'label' => __('Κόστος'),
                'class' => 'align-middle',
            ],
            'starting_price'      => [
                'label'     => __('Price'),
                'template' 	=> 'templates.input.price_quick_update',
                'class'     => 'align-middle',
            ],
            'final_price'      => [
                'label'    => __('Final price'),
                'template' => 'templates.input.price_quick_update',
                'class'    => 'align-middle',
            ],
            'vendor_cover'      => [
                'label' => __('Ποσό κάλυψης'),
                'class' => 'align-middle',
            ],
            'profit_percentage'      => [
                'label' => __('% κερδοφορίας'),
                'class' => 'align-middle',
            ],
        ];
        $data['list']['listActions'] = [
            'width' => '104',
        ];
        $data['list']['list_items'] = [];
//        $products = Product::paginate($request->input('limit', 15));
        $products = Product::filter($data['product_filters'])->orderBy('system_last_update','desc')->paginate($request->input('limit', 15));
        $data['list']['pagination'] = $products;
        $min = strtotime('01-02-2024');
        $max = strtotime('08-03-2024');
        foreach ($products as $product){
            $prices = $product->getCompetitionPriceRange();
            $val = rand($min, $max);
            $date = date('d-m-Y', $val);
            $list_item = [
                'img'                    => view('templates.column.img', [
                    'img' => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                ]),
                'name'                   => $product->name,
                'chart'                  => view('templates.column.chart', [
                    'id' => 'prices-'.$product->id,
                    'type' => 'arearange',
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
                            'name' => 'Ανταγωνισμός',
                            'data' => $prices,
                        ],
                        [
                            'name' => 'Τιμή',
                            'type'  => 'spline',
                            'data' => ProductPrice::where('product_id', $product->id)->orderBy('date','asc')->get()->map(function ($productPrice) {
                                return [strtotime($productPrice->date) * 1000, 1 * $productPrice->final_price , $productPrice->id];
                            })->toArray(),
                        ],
                    ],
                ]),
                'position'               => $product->position,
                'updated_at'             => date('Y-m-d', strtotime($product->updated_at)),
                'cheapest'               => ($product->has_lowest_price > 0) ? '<span class="fa fa-check text-success fs-3" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Φθηνότερος"></span>' : '',
                'cheapest_days'          => '',
                'priciest'               => ($product->has_highest_price > 0) ? '<span class="fa fa-check text-success fs-3" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Φθηνότερος"></span>' : '',
                'competitors_no'         => '',
                'lowest_possible_price'  => '',
                'highest_possible_price' => '',
                'entersoft_update'       => $product->system_last_update,
                'entersoft_offer'        => '',
                'value'                  => '',
                'vendor_cover'           => '',
                'profit_percentage'      => '',
                'sku'                    => $product->sku,
                'mpn'                    => $product->mpn,
                'model'                  => $product->model,
                'model_02'               => $product->model_02,
                'starting_price'         => [
                    'product_id' 	  => $product->id,
                    'price_formatted' => number_format($product->starting_price,'2',',','.').'€',
                    'price'           => $product->starting_price
                ],
                'final_price'            => [
                    'product_id' 	  => $product->id,
                    'price_formatted' => number_format($product->final_price,'2',',','.').'€',
                    'price'           => $product->final_price
                ],
                'barcode'                => $product->barcode,
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
                'href'      => route('catalog.product.info', $product->id),
                'label'     => __('Product info'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-eye',
            ];
            $list_item['actions']['update'] = [
                'class'     => 'btn btn-info text-white btn-sm',
                'type'      => 'link',
                'href'      => route('catalog.product.update', $product->id),
                'label'     => __('Update product'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-pencil',
            ];
            $list_item['actions']['delete'] = [
                'class'     => 'btn btn-danger btn-sm',
                'type'      => 'button',
                'action'    => "if(confirm('".__('Are you sure you want to delete?')."')){ window.location = '".route('catalog.product.delete', $product->id)."';}",
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
            'updated_at',
            'cheapest',
            'cheapest_days',
            'priciest',
            'competitors_no',
            'lowest_possible_price',
            'highest_possible_price',
            'entersoft_update',
            'entersoft_offer',
            'value',
            'starting_price',
            'final_price',
            'vendor_cover',
            'profit_percentage',
        ];
        $data['list']['filters_form'] = 'catalog.product';
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
                'name'  => 'has_lowest_price',
                'type'  => 'select',
                'value' => $request->input('has_lowest_price', ''),
                'label' => __('Φθηνότερος'),
                'options' => [
                    'Ναι'      => 1,
                    'Οχι'      => 0,
                    'Επιλέξτε' => '',
                ]
            ],
            [
                'name'  => 'has_highest_price',
                'type'  => 'select',
                'value' => $request->input('has_highest_price', ''),
                'label' => __('Ακριβότερος'),
                'options' => [
                    'Ναι'      => 1,
                    'Οχι'      => 0,
                    'Επιλέξτε' => '',
                ]
            ],
            [
                'name'  => 'has_competitor',
                'type'  => 'select',
                'value' => $request->input('has_competitor', ''),
                'label' => __('Έχει Ανταγωνιστή'),
                'options' => [
                    'Ναι'      => 1,
                    'Οχι'      => 0,
                    'Επιλέξτε' => '',
                ]
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

    public function delete($id, Request $request){
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect( route('catalog.product') )->with('success','Product updated successfully');
    }

    public function autocomplete(Request $request){
        $products = Product::where('name', 'like', '%'.$request->input('name').'%')->get();
        $data = [];
        foreach($products as $product){
            $data[] = [
                'value' => $product->id,
                'label' => $product->name,
            ];
        }
        return response()->json($data);
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
                $companyProduct = new CompanyProduct();
                $companyProduct->product_id = $newProduct->id;
                $companyProduct->company_id = $company_id;
                $companyProduct->save();
            }
            $products[] = $row[0];
        }
    }

    public function getMatchingProducts(Request $request){
        $json = [];
        // get company products mathing with the products of the other companies
        $companyProduct = CompanyProduct::findOrFail($request->input('id'));
        $json['name'] = $companyProduct->name;
        $json['brand'] = $companyProduct->brand;
        $json['image'] = asset('storage/'.$companyProduct->image);
        $sql= "`cp`.`id` is NULL AND ";
        $sql= "LOWER(`p`.`brand`) = LOWER('".$companyProduct->brand."') AND ";
        $sql.= "(";
        $clauses = [];
        if(!empty(trim($companyProduct->mpn))) {
            $clauses[] = "p.`model` = '".$companyProduct->mpn."' ";
            $clauses[] = "p.`mpn` = '".$companyProduct->mpn."' ";
            $clauses[] = "p.`sku` = '".$companyProduct->mpn."' ";
            $clauses[] = "p.`barcode` = '".$companyProduct->mpn."' ";
        }
        if(!empty(trim($companyProduct->barcode))) {
            $clauses[] =  "p.`model` = '".$companyProduct->barcode."' ";
            $clauses[] =  "p.`mpn` = '".$companyProduct->barcode."' ";
            $clauses[] =  "p.`sku` = '".$companyProduct->barcode."' ";
            $clauses[] =  "p.`barcode` = '".$companyProduct->barcode."' ";
        }
        if(!empty(trim($companyProduct->sku))) {
            $clauses[] = "p.`model` = '".$companyProduct->sku."' ";
            $clauses[] = "p.`mpn` = '".$companyProduct->sku."' ";
            $clauses[] = "p.`sku` = '".$companyProduct->sku."' ";
            $clauses[] = "p.`barcode` = '".$companyProduct->sku."' ";
        }

        $sql.= implode('OR ', $clauses);
        $sql.= ")";
        $products = DB::table('products as p')->selectRaw("`p`.*, `cp`.`id` ")
            ->leftJoin('company_products as cp', function ($join)  use($companyProduct) {
                $join->on('p.id', '=', 'cp.product_id')
                    ->where('cp.company_id', '=', $companyProduct->company_id);
            })
            ->whereRaw($sql)->get();
        $json['skuMatches'] = [];
        $json['matches'] = [];
        if($products->count() > 0){
            foreach($products as $product){
                $json['skuMatches'][] = [
                    'id' => $product->id,
                    'name' => $product->name
                ];
                $json['matches'][$product->id] = $product;
            }
        }

        $products = DB::table('products as p')->selectRaw("`p`.* ")
            ->leftJoin('company_products as cp', function ($join)  use($companyProduct) {
                $join->on('p.id', '=', 'cp.product_id')
                    ->where('cp.company_id', '=', $companyProduct->company_id);
            })->whereNull('cp.id')->whereRaw("LOWER(p.brand) = '".mb_strtolower($companyProduct->brand)."'")->get();

        if(1){
            $matching = [];
            foreach ($products as $product) {
                $similar = similar_text(mb_strtolower($product->name), mb_strtolower($companyProduct->name));
                if($similar > 30){
                    $matching[] = [
                        'product' => $product,
                        'id' => $companyProduct->id,
                        'name' => mb_strtolower($product->name),
                        'name2' => mb_strtolower($companyProduct->name),
                        'similar' => $similar
                    ];
                }
            }
            usort($matching, function($a, $b) {
                return $a['similar'] <= $b['similar'];
            });
            foreach($matching as $index => $match){
                $json['nameMatches'][] = $match['product'];
                $json['matches'][$match['product']->id] = $match['product'];
//                $this->line($index.': '.$match['name'].' - '.$match['name2'].' - '.$match['similar']);
                if($index > 10){
                    break;
                }
            }
        }
        if(1){
            $matching = [];
            foreach ($products as $product) {
                $similar = levenshtein(mb_strtolower($product->name), mb_strtolower($companyProduct->name));
                if($similar < 10){
                    $matching[] = [
                        'product' => $product,
                        'id' => $companyProduct->id,
                        'name' => mb_strtolower($product->name),
                        'name2' => mb_strtolower($companyProduct->name),
                        'similar' => $similar
                    ];
                }
            }
            usort($matching, function($a, $b) {
                return $a['similar'] >= $b['similar'];
            });
            foreach($matching as $index => $match){
                $json['nameLevenshteinMatches'][] = $match['product'];
                $json['matches'][$match['product']->id] = $match['product'];
                if($index > 10){
                    break;
                }
            }
        }
        if(1){
            $matching = [];
            foreach ($products as $product) {
                $similar = similar_text(mb_strtolower($product->name2), mb_strtolower($companyProduct->name));
                if($similar > 30) {
                    $matching[] = [
                        'product' => $product,
                        'id' => $companyProduct->id,
                        'name' => mb_strtolower($product->name2),
                        'name2' => mb_strtolower($companyProduct->name),
                        'similar' => $similar
                    ];
                }
            }
            usort($matching, function($a, $b) {
                return $a['similar'] <= $b['similar'];
            });

            foreach($matching as $index => $match){
                $json['matches'][$match['product']->id] = $match['product'];
                $json['skroutzNameMatches'][] = $match['product'];
                if($index > 10){
                    break;
                }
            }
        }
        if(1){
            $matching = [];
            foreach ($products as $product) {
                $similar = levenshtein(mb_strtolower($product->name2), mb_strtolower($companyProduct->name));
                if($similar < 10) {
                    $matching[] = [
                        'product' => $product,
                        'id' => $companyProduct->id,
                        'name' => mb_strtolower($product->name2),
                        'name2' => mb_strtolower($companyProduct->name),
                        'similar' => $similar
                    ];
                }
            }
            usort($matching, function($a, $b) {
                return $a['similar'] >= $b['similar'];
            });
            foreach($matching as $index => $match){
                $json['skroutzNameLevenshteinMatches'][] = $match['product'];
                $json['matches'][$match['product']->id] = $match['product'];
                if($index > 10){
                    break;
                }
            }
        }

        return response()->json($json);
    }

    public function connectCompanyProductToProduct(Request $request)
    {
        $companyProduct = CompanyProduct::find($request->company_product_id);
        $product = Product::find($request->product_id);
        $companyProduct->product_id = $product->id;
        $companyProduct->save();

        $json = [];
        $json['companyProduct'] = $companyProduct;
        $json['status'] = 1;
        $json['message'] = __('The products have been connected successfully');

        return response()->json($json);
    }
}
