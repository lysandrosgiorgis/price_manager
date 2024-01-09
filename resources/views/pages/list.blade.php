@extends('layouts.app')
@section('content')
    <div class="card">
    <div class="card-body">@include('templates.list', $list)</div>
    </div>
@endsection
@isset($list['beforeBody'])
    @foreach($list['beforeBody'] as $beforeBody)
        @push('beforeBody')
            @include($beforeBody, $list)
        @endpush
    @endforeach
@endisset
