@extends('adminlte::page')

@section('title', 'Update Product')
@section('content_header')
    <h1>Update Product</h1>
@stop

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

@section('css')
    
@endsection

@section('js')
    
@endsection
