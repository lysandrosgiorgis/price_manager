<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;

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
        $data['list'] = $this->getList();
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

    public function getList($data = []){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        $data['list']['title'] = __('Products');
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
            'mpn'      => [
                'label' => __('MPN'),
                'class' => 'align-middle',
            ],
            'sku'      => [
                'label' => __('SKU'),
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
        foreach ($products = Product::all() as $product){
            $list_item = [
                'name'      => $product->name,
                'sku'      => $product->sku,
                'mpn'      => $product->mpn,
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
}
