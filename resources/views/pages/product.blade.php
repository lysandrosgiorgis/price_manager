@extends('layouts.app')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="product-info-header d-flex">
                <div class="me-3">
                    <img src="{{ $image }}" class="img-fluid" width="200"/>
                </div>
                <div class="">
                    <span>{{ $name }}</span>
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-12 col-lg-6 ms-auto mb-2">
                            <label for="starting-price" class="mb-0">{{ __('Αρχική') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="starting_price" id="starting-price" placeholder="{{ __('Αρχική') }}" value="{{ $starting_price }}">
                                <button class="btn btn-primary " type="button" onclick="quickUpdate()">
                                    <span class="fa fa-refresh"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 ms-auto mb-2">
                            <label for="final-price" class="mb-0">{{ __('Τελική') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="final_price" id="final-price" placeholder="{{ __('Τελική') }}" value="{{ $final_price }}">
                                <input type="date" class="form-control date" data-date-format="YYYY-MM-DD" name="final_to" id="final-from" value="{{ $final_from }}">
                                <input type="date" class="form-control date" data-date-format="YYYY-MM-DD" name="final_to" id="final-to" value="{{ $final_to }}">
                                <button class="btn btn-primary " type="button" onclick="quickUpdate()">
                                    <span class="fa fa-refresh"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 ms-auto mb-2">
                            <label for="lowest-price" class="mb-0">{{ __('Κατώτερη') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="lowest_price" id="lowest-price" placeholder="{{ __('Κατώτερη') }}" value="{{ $lowest_price }}">
                                <button class="btn btn-primary " type="button" onclick="quickUpdate()">
                                    <span class="fa fa-refresh"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 ms-auto mb-2">
                            <label for="highest-price" class="mb-0">{{ __('Ανώτερη') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="highest_price" id="highest-price" placeholder="{{ __('Ανώτερη') }}" value="{{ $highest_price }}">
                                <button class="btn btn-primary " type="button" onclick="quickUpdate()">
                                    <span class="fa fa-refresh"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="home-table product-competitors-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Ανταγωνιστής') }}</th>
                            <th class="column-chart">{{ __('Πορεία Τιμής') }}</th>
                            <th>{{ __('Τιμή') }}</th>
                            <th>{{ __('Ημ/νία') }}</th>
                            <th>{{ __('Link') }}</th>
                            <th>{{ __('Περιγραφή') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{!! $chart !!}</td>
                            <td class="plus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                            <td class="text-center"><a href="" target="_blank" class="btn btn-warning text-white"><span class="fa-solid fa-link"></span></a></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#desc">
                                    <span class="fa-solid fa-circle-info"></span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{!! $chart !!}</td>
                            <td class="minus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                            <td class="text-center"><a href="" target="_blank" class="btn btn-warning text-white"><span class="fa-solid fa-link"></span></a></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#desc">
                                    <span class="fa-solid fa-circle-info"></span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{!! $chart !!}</td>
                            <td class="minus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                            <td class="text-center"><a href="" target="_blank" class="btn btn-warning text-white"><span class="fa-solid fa-link"></span></a></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#desc">
                                    <span class="fa-solid fa-circle-info"></span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Competitor</td>
                            <td class="column-chart">{!! $chart !!}</td>
                            <td class="plus-price fw-bold">10.00€</td>
                            <td>08-03-2024</td>
                            <td class="text-center"><a href="" target="_blank" class="btn btn-warning text-white"><span class="fa-solid fa-link"></span></a></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#desc">
                                    <span class="fa-solid fa-circle-info"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="desc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Περιγραφή') }}</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('Περιγραφή κείμενο') }}
                </div>
            </div>
        </div>
    </div>
@endsection
