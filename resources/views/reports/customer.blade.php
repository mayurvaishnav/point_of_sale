@extends('adminlte::page')

@section('title', 'Customer Report')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                @if (isset($currentCustomer))
                    <h1>{{ $currentCustomer->name }}</h1>
                    <span>{{ $currentCustomer->email }}</span><br>
                    <span>{{ $currentCustomer->phone }}</span><br>
                    <span>{{ $currentCustomer->address }}</span>
                    
                @endif
            </div>
            <div class="col-sm-8">
                <form id="dateFilterForm" method="GET" action="{{ route('reports.customer') }}" class="form-inline float-right">
                    <div class="form-group">
                        <input type="date" name="start_date" id="orderDate" class="form-control" value="{{ request('start_date') }}" required>
                    </div>
                    <div class="form-group ml-2">
                        <input type="date" name="end_date" id="orderDate" class="form-control" value="{{ request('end_date') }}" required>
                    </div>
                    <div class="form-group ml-2">
                        <select class="form-control select2" name="customer_id" id="customerSelect" required>
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ ($currentCustomer->id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary ml-2">Filter</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('content')

@include('layouts.alerts')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Order Date</th>
                            <th>Invoice No.</th>
                            <th>Order Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->invoice_number }}</td>
                                <td>{!! $order->getStatusBadge() !!}</td>
                                <td>{{ $order->total_after_discount }}</td>
                                <td>
                                    @can('order-show')
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">Details</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // // Initialize Select2 for customers
            $('#customerSelect').select2();
        });
        
    </script>
@stop