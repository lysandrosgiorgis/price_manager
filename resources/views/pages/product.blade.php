@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="product-info-header">
                <img src="{{ $image }}" class="img-fluid" width="200"/>
                <span>{{ $name }}</span>
            </div>
            <div class="home-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Ανταγωνιστής') }}</th>
                            <th class="column-chart">{{ __('Chart') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{{ $chart  }}</td>
                            <td class="plus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{{ $chart  }}</td>
                            <td class="minus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{{ $chart  }}</td>
                            <td class="minus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{{ $chart  }}</td>
                            <td class="plus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
