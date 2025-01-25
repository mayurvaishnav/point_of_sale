@extends('adminlte::page')

@section('title', 'Create Category')
@section('content_header')
    <h1>Create New Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('categories.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')

@stop

@section('js')

@stop
