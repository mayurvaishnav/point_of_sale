@extends('adminlte::page')

@section('title', 'Create Role')
@section('content_header')
    <h1>Create New Role</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('auth.roles.fields')

            </form>
        </div>
    </div>
@endsection
