@extends('layouts.app')
@section('content')
    <div class="products-container">
        @foreach($list['list_items'] as $productIndex => $productItem)
            <div class="single-product-container card">
                <div class="card-body">
                    <div class="card-header">
                        #{{ $productItem['id'] }} {{ $productItem['name'] }}
                        @if($productItem['final_price'] != $productItem['starting_price'])
                            <span class="float-end px-2">{{ $productItem['final_price'] }}€</span>
                            <span class="float-end px-2"><s>{{ $productItem['starting_price'] }}€</s></span>
                        @else
                            <span class="float-end px-2">{{ $productItem['starting_price'] }}€</span>
                        @endif
                    </div>
                    <div class="competitor-products">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                @foreach($productItem['company_products'] as $companyProductIndex => $companyProductItem)
                                    <tr>
                                        <td>#{{ $companyProductItem['id'] }} {{ $companyProductItem['company_name'] }}</td>
                                        <td> {{ $companyProductItem['url'] }}</td>
                                        <td class="columnActions text-end" @isset($list['listActions']['width'])width="{{ $list['listActions']['width'] }}"@endisset>
                                            @if(count($companyProductItem['urls']) > 0)
                                            <button class="btn btn-outline-secondary position-relative"
                                                    data-bs-title="{{ __('Suggestions') }}"
                                                    title="{{ __('Suggestions') }}"
                                                    data-dnd-toggle="tooltip"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#productsUrlSuggestions-{{ $productItem['id'] }}" >
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                                <span class="visually-hidden">{{ __('Suggestions') }}</span>
                                                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">{{ count($companyProductItem['urls']) }}</span>
                                            </button>
                                                <div class="modal fade" id="productsUrlSuggestions-{{ $productItem['id'] }}" tabindex="-1" aria-labelledby="productsUrlSuggestionsLabel-{{ $productItem['id'] }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="productsImportModalLabel-{{ $productItem['id'] }}">{{ __('Url Suggestions for :productName', ['productName' => $productItem['name']]) }}</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-sm text-start">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{ __('Name') }}</th>
                                                                            <th>{{ __('URL') }}</th>
                                                                            <th>
                                                                                <button class="btn btn-danger"
                                                                                        onclick="massChangeUrlStatus('{{ $companyProductItem['id'] }}', 'wrong', this);"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-title="{{ __('Discard all URL') }}"
                                                                                        data-bs-original-title="{{ __('Discard all URL') }}" >
                                                                                    <span class="fa fa-times" aria-hidden="true"></span>
                                                                                    <span class="visually-hidden">{{ __('Discard all URL') }}</span>
                                                                                </button>
                                                                            </th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($companyProductItem['urls'] as $url)
                                                                            <tr>
                                                                                <td>{{ $url->name }}</td>
                                                                                <td>{{ $url->url }}</td>
                                                                                <td>
                                                                                    <button class="btn btn-danger"
                                                                                            onclick="changeUrlStatus('{{ $url->id }}', 'wrong', this, this.parentNode.parentNode);"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-title="{{ __('Select URL') }}"
                                                                                            data-bs-original-title="{{ __('Discard URL') }}" >
                                                                                        <span class="fa fa-times" aria-hidden="true"></span>
                                                                                        <span class="visually-hidden">{{ __('Discard URL') }}</span>
                                                                                    </button>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-success"
                                                                                            onclick="acceptProductUrl('{{ $url->id }}', this);"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-title="{{ __('Select URL') }}"
                                                                                            data-bs-original-title="{{ __('Select URL') }}" >
                                                                                        <span class="fa fa-check" aria-hidden="true"></span>
                                                                                        <span class="visually-hidden">{{ __('Select URL') }}</span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif
                                            <a
                                                href="{{ $companyProductItem['update'] }}"
                                                class="btn btn-info text-white"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Update product"
                                                data-bs-original-title="Update product"
                                            >
                                                <span class="fa fa-pencil" aria-hidden="true"></span>
                                                <span class="visually-hidden">Update product</span>
                                            </a>
                                            <button
                                                onclick="if(confirm('Are you sure you want to delete?')){ window.location = '{{ $companyProductItem['delete'] }}';}"
                                                class="btn btn-danger"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Delete product"
                                                data-bs-original-title="Delete product"
                                            >
                                                <span class="fa fa-trash" aria-hidden="true"></span>
                                                <span class="visually-hidden">Delete product</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $list['pagination']->links() }}
    <script>
    </script>
@endsection

