@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-5">
        <div class="col-sm-6 col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Φθηνότερα') }}</h6>
                <div class="data-card-numbers">
                    <i class="fa-solid fa-down-long green"></i>
                    <span class="card-number">{{ $lowest_product_count }}</span>
                    <span class="plus-percentage-number">{{ $lowest_product_percentage }}</span>
                </div>
                <a href="{{ $lowest_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Ακριβότερα') }}</h6>
                <div class="data-card-numbers">
                    <i class="fa-solid fa-up-long red"></i>
                    <span class="card-number">{{ $highest_product_count }}</span>
                    <span class="minus-percentage-number">{{ $highest_product_percentage }}</span>
                </div>
                <a href="{{ $highest_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Χωρις ανταγωνιστή') }}</h6>
                <div class="data-card-numbers">
                    <i class="fa-solid fa-right-left yellow"></i>
                    <span class="card-number">{{ $no_competitor_products_count }}</span>
                    <span class="plus-percentage-number">{{ $no_competitor_products_percentage }}</span>
                </div>
                <a href="{{ $competitor_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Πρόσφατες Αλλαγές') }}</h6>
                <div class="data-card-numbers">
                    <i class="fa-solid fa-clock-rotate-left blue"></i>
                    <span class="card-number">{{ $latest_update_product_count }}</span>
                    <span class="plus-percentage-number">{{ $latest_update_product_percentage }}</span>
                </div>
                <a href="{{ $latest_update_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mt-4">
            <div class="home-table border">
                <div class="home-table-header text-center fw-b fs-4 mb-2">{{ __('Φθηνοτερα') }}</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Όνομα') }}</th>
                            <th>{{ __('Ανταγωνιστής') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                            <th>{{ __('Τιμή Αντ.') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowest_products as $product)
                            <tr>
                                <td><img src="{{ $product['image'] }}" width="50"/></td>
                                <td><a href="{{ $product['link'] }}">{{ $product['name'] }}</a></td>
                                <td>{{ $product['competitors'] }}</td>
                                <td>{{ $product['update_date'] }}</td>
                                <td class="plus-price fw-bold">{{ $product['price'] }}</td>
                                <td class="minus-price fw-bold">{{ $product['competitor_price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ $lowest_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mt-4">
            <div class="home-table border">
                <div class="home-table-header text-center fw-b fs-4 mb-2">{{ __('Ακριβότερα') }}</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Όνομα') }}</th>
                            <th>{{ __('Ανταγωνιστής') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                            <th>{{ __('Τιμή Αντ.') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($highest_products as $product)
                            <tr>
                                <td><img src="{{ $product['image'] }}" width="50"/></td>
                                <td><a href="{{ $product['link'] }}">{{ $product['name'] }}</a></td>
                                <td>{{ $product['competitors'] }}</td>
                                <td>{{ $product['update_date'] }}</td>
                                <td class="minus-price fw-bold">{{ $product['price'] }}</td>
                                <td class="plus-price fw-bold">{{ $product['competitor_price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ $highest_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mt-4">
            <div class="home-table border">
                <div class="home-table-header text-center fw-b fs-4 mb-2">{{ __('Χωρις ανταγωνιστή') }}</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Όνομα') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($no_competitor_products as $product)
                            <tr>
                                <td><img src="{{ $product['image'] }}" width="50"/></td>
                                <td><a href="{{ $product['link'] }}">{{ $product['name'] }}</a></td>
                                <td>{{ $product['update_date'] }}</td>
                                <td class="neutral-price fw-bold">{{ $product['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ $competitor_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mt-4">
            <div class="home-table border">
                <div class="home-table-header text-center fw-b fs-4 mb-2">{{ __('Πρόσφατες Αλλαγές') }}</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Όνομα') }}</th>
                            <th>{{ __('Ανταγωνιστής') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                            <th>{{ __('Τιμή Αντ.') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latest_update_products as $product)
                            <tr>
                                <td><img src="{{ $product['image'] }}" width="50"/></td>
                                <td><a href="{{ $product['link'] }}">{{ $product['name'] }}</a></td>
                                <td>{{ $product['competitors'] }}</td>
                                <td>{{ $product['update_date'] }}</td>
                                {!! $product['price'] !!}
                                {!! $product['competitor_price'] !!}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ $latest_update_link }}" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Dashboard') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    {{ __('You are logged in!') }}--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>
@endsection
