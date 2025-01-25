@extends('adminlte::page')

@section('title', 'Create Product')
@section('content_header')
    <h1>Create New Product</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('products.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')

@stop

@section('js')

@stop
