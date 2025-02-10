@extends('layouts.adminlte-app')

@section('title', 'Create Product')
@section('custom_content_header')
    <h1>Create New Product</h1>
@stop

@section('custom_content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('products.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')

@stop

@section('custom_js')

@stop
