@extends('layouts.adminlte-app')

@section('title', 'Create Customer')
@section('custom_content_header')
    <h1>Create New Customer</h1>
@stop

@section('custom_content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('customers.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')

@stop

@section('custom_js')

@stop
