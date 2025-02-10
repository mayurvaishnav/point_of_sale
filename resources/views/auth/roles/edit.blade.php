@extends('layouts.adminlte-app')

@section('title', 'Update Role')
@section('custom_content_header')
    <h1>Update Role</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('roles.update', $role) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('auth.roles.fields')
            </form>
        </div>
    </div>
@endsection
