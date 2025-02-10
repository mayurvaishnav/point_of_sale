@extends('layouts.adminlte-app')

@section('title', 'Create Supplier')
@section('custom_content_header')
    <h1>Create New Supplier</h1>
@stop

@section('custom_content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('suppliers.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')

@stop

@section('custom_js')

@stop
