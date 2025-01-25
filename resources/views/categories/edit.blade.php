@extends('adminlte::page')

@section('title', 'Update Category')
@section('content_header')
    <h1>Update Category</h1>
@stop

@section('content')

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

@section('css')
    
@endsection

@section('js')
    
@endsection
