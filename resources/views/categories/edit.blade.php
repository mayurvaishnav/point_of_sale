@extends('layouts.adminlte-app')

@section('title', 'Update Category')
@section('custom_content_header')
    <h1>Update Category</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('categories.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    
@endsection

@section('custom_js')
    
@endsection
