@extends('layouts.adminlte-app')

@section('title', 'Create Category')
@section('custom_content_header')
    <h1>Create New Category</h1>
@stop

@section('custom_content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('categories.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')

@stop

@section('custom_js')

@stop
