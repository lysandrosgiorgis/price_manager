@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">@include('templates.form', $form)</div>
    </div>
@endsection
