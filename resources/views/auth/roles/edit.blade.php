@extends('adminlte::page')

@section('title', 'Update Role')
@section('content_header')
    <h1>Update Role</h1>
@stop

@section('content')

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
