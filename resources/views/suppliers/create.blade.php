@extends('adminlte::page')

@section('title', 'Create Supplier')
@section('content_header')
    <h1>Create New Supplier</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('suppliers.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')

@stop

@section('js')

@stop
