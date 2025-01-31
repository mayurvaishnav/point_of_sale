@extends('adminlte::page')

@section('title', 'Update Product')
@section('content_header')
    <h1>Update Product</h1>
@stop

@section('css')
<style>
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
    }

    .card-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
@endsection

@section('content')

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


@section('js')

    @include('products.js')
    
@endsection
