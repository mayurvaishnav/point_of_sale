@extends('layouts.adminlte-app')

@section('title', 'Update Job')
@section('custom_content_header')
    <h1>Update Sechdule Job</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('jobs.update', $job) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('jobs.fields')
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    
@endsection

@section('custom_js')
    
@endsection
