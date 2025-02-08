@extends('adminlte::page')

@section('title', 'Orders')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $pageTitle }}</h1>
            </div>
            <div class="col-sm-6">
                @if ($pageTitle == 'All Orders')
                    <form id="dateFilterForm" method="GET" action="{{ route('orders.index') }}" class="form-inline float-right">
                        <div class="form-group">
                            <label for="orderDate" class="mr-2">Select Date:</label>
                            <input type="date" name="order_date" id="orderDate" class="form-control" value="{{ request('order_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary ml-2">Filter</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('content')

@include('layouts.alerts')


@endsection

@section('js')
    <script>
        
    </script>
@stop