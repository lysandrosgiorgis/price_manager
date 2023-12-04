<?php

namespace App\Http\Controllers\Competition;

use App\Http\Controllers\Controller;
use App\Models\Competition\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $filters = [
        'name' => '',
        'status' => '',
        'sync' => '',
        'created_at' => '',
        'updated_at' => '',
        'sort' => '',
        'order' => '',
    ];

    public function companyList(Request $request){
        $data['page_title'] = __('Companies');
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
            'url' 		=> 'required',
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);
        $company = new company();
        $company->name 		    = $request->name;
        $company->url 	        = $request->url;
        $company->image 	    = $request->image;
        $company->status 	    = $request->status;
        $company->sync 	        = $request->sync;
        $company->save();


        if($request->saveAndEdit == 1){
            return redirect( route('competition.company.update', $company->id) )->with('success','Company updated successfully');
        }else{
            return redirect( route('competition.company') )->with('success','Company updated successfully');
        }
    }

    public function edit($id){
        $company = Company::findOrFail($id);

        $data['form'] = $this->getForm($company);

        return view('pages.form', $data);
    }

    public function update($id, Request $request){
        $company = Company::findOrFail($id);

        $validatedData = $request->validate([
            'name' 		=> 'required',
            'url' 	    => 'url',
            'status' 	=> 'required',
            'sync' 	    => 'required',
        ]);

        $company->name 		    = $request->name;
        $company->url 	        = $request->url;
        $company->image 	    = $request->image;
        $company->status 	    = $request->status;
        $company->sync 	        = $request->sync;
        $company->save();


        if($request->saveAndEdit == 1){
            return redirect( route('competition.company.update', $company->id) )->with('success','Company updated successfully');
        }else{
            return redirect( route('competition.company') )->with('success','Company updated successfully');
        }
    }

    public function getForm(Company $company = null){
        $url = [];
        foreach($this->filters as $filter){
            if (isset($this->request->get[$filter])) {
                $url[] = $filter.'='.$this->request->get[$filter];
            }
        }

        if($company){
            $data['pageTitle'] = __('Edit :companyName', ['companyName' => $company->name]);
            $data['form']['action'] = route('competition.company.update', $company->id, $url);
            $data['form']['id'] = 'catalogCompanyUpdate';
        }else{
            $data['pageTitle'] = __('Create company');
            $data['form']['action'] = route('competition.company.create');
            $data['form']['id'] = 'catalogCompanyCreate';
        }

        $data['form']['buttons']['bottom']['back'] = [
            'type'  => 'link',
            'id'  => $data['form']['id'].'BackBtn',
            'href'  => route('competition.company', $url),
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
            'url'   => '',
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
                                'value' 	=> old('name', $company ? $company->name : $defaults['name'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'url',
                                'label' 	=> __('URL'),
                                'type' 		=> 'text',
                                'wide' 		=> 1,
                                'value' 	=> old('url', $company ? $company->url : $defaults['url'] ),
                                'error'     => '',
                            ],
                            [
                                'name' 		=> 'image',
                                'label' 	=> __('Image'),
                                'wide' 		=> 1,
                                'type' 		=> 'image',
                                'value' 	=> old('image', $company ? $company->image : $defaults['image'] ),
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
                                'value' 	=> old('status', $company ? $company->status : $defaults['status'] ),
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
                                'value' 	=> old('sync', $company ? $company->sync : $defaults['sync'] ),
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

        $data['list']['title'] = __('Companies');
        $data['list']['buttons']['top'][] = [
            'class' => 'btn btn-success ',
            'type'  => 'link',
            'href'  => route('competition.company.create'),
            'label' => __('Create company'),
            'icon' => 'fa fa-plus',
        ];
        $data['list']['columns'] = [
            'name'      => [
                'label' => __('Name'),
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
        foreach ($companies = Company::all() as $company){
            $list_item = [
                'name'      => $company->name,
                'status'    => view('templates.column.icon', [
                    'icon' => ($company->status == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($company->status == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
                'sync'    => view('templates.column.icon', [
                    'icon' => ($company->sync == 'active') ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                    'label' => ($company->sync == 'active') ? __('Active') : __('Inactive'),
                    'tooltip' => 1,
                    'hideLabel' => 1,
                ]),
            ];
            $list_item['actions']['update'] = [
                'class' => 'btn btn-info text-white',
                'type'  => 'link',
                'href'  => route('competition.company.update', $company->id),
                'label' => __('Update Company'),
                'hideLabel' => 1,
                'tooltip' => 1,
                'icon' => 'fa fa-pencil',
            ];
            $list_item['actions']['delete'] = [
                'class' => 'btn btn-danger',
                'type'  => 'link',
                'href'  => route('competition.company.delete', $company->id),
                'label' => __('Delete company'),
                'hideLabel' => 1,
                'tooltip' => 1,
                'icon' => 'fa fa-trash',
            ];
            $list_item['actions']['delete'] = [
                'class' => 'btn btn-danger',
                'type'  => 'button',
                'action'  => "if(confirm('".__('Are you sure you want to delete?')."')){ window.location = '".route('competition.company.delete', $company->id)."';}",
                'label' => __('Delete company'),
                'hideLabel' => 1,
                'tooltip' => 1,
                'icon' => 'fa fa-trash',
            ];
            $data['list']['list_items'][] = $list_item;
        }

        return $data['list'];
    }

    public function delete($id, Request $request){
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect( route('competition.company') )->with('success','Company updated successfully');
    }
}
