@extends('layouts.app')
@section('content')
    <div class="products-container">
        @foreach($products as $productIndex => $productItem)
            <div class="single-product-container card">
                <div class="card-body">
                    <div class="card-header">
                        {{ $productItem['name'] }}
                        @if($productItem['final_price'])
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
                                        <td> {{ $companyProductItem['company_name'] }}</td>
                                        <td> {{ $companyProductItem['url'] }}</td>
                                        <td class="columnActions">
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
@endsection
