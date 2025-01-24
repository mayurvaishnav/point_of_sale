@extends('adminlte::page')

@section('title', 'Create Customer')
@section('content_header')
    <h1>Create New Customer</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('customers.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')

@stop

@section('js')

@stop
