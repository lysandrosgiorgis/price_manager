<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $filters = [
        'name' => '',
        'status' => '',
        'sort' => '',
        'order' => '',
    ];

    public function categoryList(Request $request){
        $data['page_title'] = __('Categorries');

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
        ]);

        $category = new Category();
        $category->name         = $request->name;
        $category->parent_id    = $request->parent_id ;
        $category->url          = $request->url ;
        $category->status       = $request->status;
        $category->image        = $request->image;
        $category->save();

        if($request->saveAndEdit == 1){
            return redirect( route('catalog.category.update', $category->id) )->with('success','Category created successfully');
        }else{
            return redirect( route('catalog.category') )->with('success','Category created successfully');
        }
    }

    public function edit($id){
        $category = Category::findOrFail($id);

        $data['form'] = $this->getForm($category);

        return view('pages.form', $data);
    }

    public function update($id, Request $request){
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' 		=> 'required',
            'status' 	=> 'required',
        ]);

        $category->name 	    = $request->name;
        $category->image 	    = $request->image;
        $category->parent_id 	= $request->parent_id;
        $category->url 	        = $request->url;
        $category->status 	    = $request->status;
        $category->save();

        if($request->saveAndEdit == 1){
            return redirect( route('catalog.category.update', $category->id) )->with('success','Category updated successfully');
        }else{
            return redirect( route('catalog.category') )->with('success','Category updated successfully');
        }
    }

    public function getForm(Category $category = null){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        if($category){
            $data['pageTitle'] = __('Edit :Name', ['Name' => $category->name]);
            $data['form']['action'] = route('catalog.category.update', $category->id, $url);
            $data['form']['id'] = 'catalogCategoryUpdate';
        }else{
            $data['pageTitle'] = __('Create category');
            $data['form']['action'] = route('catalog.category.create');
            $data['form']['id'] = 'catalogCategoryCreate';
        }

        $data['form']['buttons']['bottom']['back'] = [
            'type'  => 'link',
            'id'  => $data['form']['id'].'BackBtn',
            'href'  => route('catalog.category', $url),
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
            'name'      => '',
            'parent_id' => 0,
            'status'    => 'active',
            'image'     => '',
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
                                'value' 	=> old('name', $category ? $category->name : $defaults['name'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		            => 'parent_id',
                                'label' 	            => __('Parent'),
                                'autocomplete_name'     => 'parent',
                                'autocomplete_value'    => old('parent', $category && $category->parent_id ? $category->parent->name : '' ),
                                'type' 		            => 'autocomplete',
                                'source'                => route('catalog.category.autocomplete'),
                                'value' 	            => old('parent_id', $category ? $category->parent_id : $defaults['parent_id'] ),
                                'error'                 => '',
                            ],
                            [
                                'name' 		=> 'url',
                                'label' 	=> __('URL'),
                                'wide' 		=> 1,
                                'type' 		=> 'text',
                                'value' 	=> old('url', $category ? $category->url : $defaults['url'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'image',
                                'label' 	=> __('Image'),
                                'wide' 		=> 1,
                                'type' 		=> 'image',
                                'value' 	=> old('image', $category ? $category->image : $defaults['image'] ),
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
                                'value' 	=> old('status', $category ? $category->status : $defaults['status'] ),
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
        $data['list_filters'] = $request->all();

        $data['list']['title'] = __('Categories');

        $data['list']['buttons']['top'][] = [
            'class' => 'btn btn-success ',
            'type'  => 'link',
            'href'  => route('catalog.category.create'),
            'label' => __('Create category'),
            'icon' => 'fa fa-plus',
        ];
        $data['list']['columns'] = [
            'name'      => [
                'label' => __('Όνομα'),
                'class' => 'align-middle',
            ],
        ];
        $data['list']['listActions'] = [
            'width' => '104',
        ];
        $data['list']['list_items'] = [];
        $categoryItems = Category::filter($data['list_filters'])->simplePaginate($request->input('limit', 15));
//            ->paginate();
        $data['list']['pagination'] = $categoryItems;

        foreach ($categoryItems as $item){
            $list_item = [
                'name'  => $item->name,
            ];
            $list_item['actions']['update'] = [
                'class'     => 'btn btn-info text-white btn-sm',
                'type'      => 'link',
                'href'      => route('catalog.category.update', $item->id),
                'label'     => __('Update category'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-pencil',
            ];
            $list_item['actions']['delete'] = [
                'class'     => 'btn btn-danger btn-sm',
                'type'      => 'button',
                'action'    => "if(confirm('".__('Are you sure you want to delete?')."')){ window.location = '".route('catalog.category.delete', $item->id)."';}",
                'label'     => __('category category'),
                'hideLabel' => 1,
                'tooltip'   => 1,
                'icon'      => 'fa fa-trash',
            ];
            $data['list']['list_items'][] = $list_item;
        }
        $data['list']['visibleColumns'] = [
            'name',
        ];
        $data['list']['filters_form'] = 'catalog.category';
        $data['list']['filters'] = [
            [
                'name'  => 'name',
                'type'  => 'text',
                'value' => $request->input('name', ''),
                'label' => __('Όνομα'),
            ],
        ];

        return $data['list'];
    }

    public function delete($id, Request $request){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect( route('catalog.category') )->with('success','Category updated successfully');
    }

    public function autocomplete(Request $request){
        $categories = Category::where('name', 'like', '%'.$request->input('name').'%')->get();
        $data = [];
        foreach($categories as $category){
            $data[] = [
                'value'    => $category->id,
                'label' => $category->name,
            ];
        }
        return response()->json($data);
    }
}
