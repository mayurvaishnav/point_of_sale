@extends('layouts.adminlte-app')

@section('title', 'Create Role')
@section('custom_content_header')
    <h1>Create New Role</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('auth.roles.fields')

            </form>
        </div>
    </div>
@endsection
