<?php

namespace App\Http\Controllers\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Competition\Product as CompanyProduct;

class ProductController extends Controller
{
    public function companyProductList(Request $request){
        $data['page_title'] = __('Products');
        $data['list']   = $this->getList($request);
        return view('pages.companyProductList', $data);
    }

    public function getList($request, $data = []){
        $products = Product::paginate($request->input('limit', 15));
        $data['list']['pagination'] = $products;
        $data['list']['listActions'] = [
            'width' => '156',
        ];
        $data['list']['list_items'] = [];
        foreach ($products as $product){
            $company_products = $product->companyProducts;
            $company_products_array = [];
            foreach ($company_products as $companyProductIndex => $companyProductItem){
                $urls = [];
                foreach($companyProductItem->urls as $url){
                    if($url->status == 'wrong') continue;
                    $urls[] = $url;
                }
                $company_products_array[] = [
                    'id'            => $companyProductItem->id,
                    'company_name'  => $companyProductItem->company->name,
                    'url'           => $companyProductItem->url,
                    'urls'          => $urls,
                    'update'        => route('competition.product.update', $companyProductItem->id),
                    'delete'        => route('competition.product.delete', $companyProductItem->id),
                ];
            }
            $product_item = [
                'id'               => $product->id,
                'name'             => $product->name,
                'sku'              => $product->sku,
                'mpn'              => $product->mpn,
                'barcode'          => $product->barcode,
                'starting_price'   => round($product->starting_price, 2),
                'final_price'      => round($product->final_price, 2),
                'company_products' => $company_products_array,
            ];
            $data['list']['list_items'][] = $product_item;
        }

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
//            'name' 		=> 'required',
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);

//        $companyProduct->name 		    = $request->name;
        $companyProduct->url 	        = $request->url;
//        $companyProduct->image 	        = $request->image;
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
//        $url = [];
//        foreach($this->filters as $filter){
//            if (isset($this->request->get[$filter])) {
//                $url[] = $filter.'='.$this->request->get[$filter];
//            }
//        }

        if($companyProduct){
            $data['pageTitle'] = __('Edit :companyName', ['companyName' => $companyProduct->name]);
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
            'name'          => '',
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
//                            [
//                                'name' 		=> 'name',
//                                'label' 	=> __('Name'),
//                                'type' 		=> 'text',
//                                'wide' 		=> 1,
//                                'value' 	=> old('name', $companyProduct ? $companyProduct->name : $defaults['name'] ),
//                                'error'     => '',
//                            ],
                            [
                                'name' 		=> 'url',
                                'label' 	=> __('URL'),
                                'type' 		=> 'text',
                                'wide' 		=> 1,
                                'value' 	=> old('url', $companyProduct ? $companyProduct->url : $defaults['url'] ),
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
        ];

        return $data;
    }

    public function delete($id, Request $request){
        $company_product = CompanyProduct::findOrFail($id);
        $company_product->delete();
        return redirect( route('competition.product') )->with('success','Product updated successfully');
    }
}
