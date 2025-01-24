@extends('adminlte::page')

@section('title', 'Update Customer')
@section('content_header')
    <h1>Update Customer</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('customers.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection
