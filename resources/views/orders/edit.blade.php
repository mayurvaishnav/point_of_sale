@extends('layouts.adminlte-app')

@section('title', 'Update Product')
@section('custom_content_header')
    <h1>Update Product</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('products.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    
@endsection

@section('custom_js')
    
@endsection
