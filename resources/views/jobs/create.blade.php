@extends('layouts.adminlte-app')

@section('title', 'Create Job')
@section('custom_content_header')
    <h1>Create New Schedule Job</h1>
@stop

@section('custom_content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('jobs.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')

@stop

@section('custom_js')

@stop
