@extends('adminlte::page')

@section('content_header')
<h1>Testing......</h1>
    @yield('custom_content_header')
@stop

@section('css')
    @yield('custom_css')
@stop

@section('content')
    @include('layouts.alerts')
    @yield('custom_content')

@endsection

@section('js')
    @yield('custom_js')

@endsection