@extends('layouts.adminlte-app')

@section('title', 'Update User')
@section('custom_content_header')
    <h1>Update User</h1>
@stop

@section('custom_content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('auth.users.fields')
            </form>
        </div>
    </div>
@endsection
