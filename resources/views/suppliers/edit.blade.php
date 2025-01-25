@extends('adminlte::page')

@section('title', 'Update Supplier')
@section('content_header')
    <h1>Update Supplier</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('suppliers.fields')
            </form>
        </div>
    </div>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection
