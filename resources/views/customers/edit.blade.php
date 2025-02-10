@extends('layouts.adminlte-app')

@section('title', 'Update Customer')
@section('custom_content_header')
    <h1>Update Customer</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('customers.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    
@endsection

@section('custom_js')
    
@endsection
