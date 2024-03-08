@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-5">
        <div class="col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Φθηνότερα') }}</h6>
                <div class="data-card-numbers">
                    <span class="fa fa-chart-simple"></span>
                    <span class="card-number">9999</span>
                    <span class="plus-percentage-number">+10%</span>
                </div>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Ακριβότερα') }}</h6>
                <div class="data-card-numbers">
                    <span class="fa fa-chart-simple"></span>
                    <span class="card-number">9999</span>
                    <span class="minus-percentage-number">+10%</span>
                </div>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Χωρις ανταγωνιστή') }}</h6>
                <div class="data-card-numbers">
                    <span class="fa fa-chart-simple"></span>
                    <span class="card-number">9999</span>
                    <span class="plus-percentage-number">+10%</span>
                </div>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="data-card border">
                <h6 class=""> {{ __('Πρόσφατες Αλλαγές') }}</h6>
                <div class="data-card-numbers">
                    <span class="fa fa-chart-simple"></span>
                    <span class="card-number">9999</span>
                    <span class="plus-percentage-number">+10%</span>
                </div>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="home-table">
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
                    <tr>
                        <td><img src=""/></td>
                        <td>Name</td>
                        <td>Competitor</td>
                        <td>08-03-2024</td>
                        <td class="plus-price fw-bold">10.00€</td>
                        <td class="minus-price fw-bold">12.00€</td>
                    </tr>
                    <tr>
                        <td><img src=""/></td>
                        <td>Name 2</td>
                        <td>Competitor 2</td>
                        <td>08-03-2024</td>
                        <td class="plus-price fw-bold">10.00€</td>
                        <td class="minus-price fw-bold">12.00€</td>
                    </tr>
                    </tbody>
                </table>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="home-table">
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
                    <tr>
                        <td><img src=""/></td>
                        <td>Name</td>
                        <td>Competitor</td>
                        <td>08-03-2024</td>
                        <td class="minus-price fw-bold">10.00€</td>
                        <td class="plus-price fw-bold">12.00€</td>
                    </tr>
                    <tr>
                        <td><img src=""/></td>
                        <td>Name 2</td>
                        <td>Competitor 2</td>
                        <td>08-03-2024</td>
                        <td class="minus-price fw-bold">10.00€</td>
                        <td class="plus-price fw-bold">12.00€</td>
                    </tr>
                    </tbody>
                </table>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="home-table">
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
                    <tr>
                        <td><img src=""/></td>
                        <td>Name</td>
                        <td>08-03-2024</td>
                        <td>10.00€</td>
                    </tr>
                    <tr>
                        <td><img src=""/></td>
                        <td>Name 2</td>
                        <td>08-03-2024</td>
                        <td>10.00€</td>
                    </tr>
                    </tbody>
                </table>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="home-table">
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
                    <tr>
                        <td><img src=""/></td>
                        <td>Name</td>
                        <td>Competitor</td>
                        <td>08-03-2024</td>
                        <td class="minus-price fw-bold">12.00€</td>
                        <td class="plus-price fw-bold">10.00€</td>
                    </tr>
                    <tr>
                        <td><img src=""/></td>
                        <td>Name 2</td>
                        <td>Competitor 2</td>
                        <td>08-03-2024</td>
                        <td class="plus-price fw-bold">10.00€</td>
                        <td class="minus-price fw-bold">12.00€</td>
                    </tr>
                    </tbody>
                </table>
                <a href="" class="border border p-1 mt-2 text-center d-block">{{ __('Δείτε τα όλα') }}</a>
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
