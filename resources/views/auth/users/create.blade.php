@extends('layouts.adminlte-app')

@section('title', 'Create User')
@section('custom_content_header')
    <h1>Create New User</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('auth.users.fields')

            </form>
        </div>
    </div>
@endsection
