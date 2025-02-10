@extends('layouts.adminlte-app')

@section('title', 'Update Supplier')
@section('custom_content_header')
    <h1>Update Supplier</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('suppliers.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    
@endsection

@section('custom_js')
    
@endsection
