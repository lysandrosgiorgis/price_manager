@extends('layouts.app')
@section('content')
    <div class="card">
    <div class="card-body">@include('templates.list', $list)</div>
    </div>
@endsection
