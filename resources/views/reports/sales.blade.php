@extends('adminlte::page')

@section('title', '')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sales Summary</h1>
            </div>
            <div class="col-sm-6">
                <form id="dateFilterForm" method="GET" action="{{ route('reports.sales') }}" class="form-inline float-right">
                    <div class="form-group">
                        <input type="date" name="start_date" id="orderDate" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group ml-2">
                        <input type="date" name="end_date" id="orderDate" class="form-control" value="{{ request('end_date') }}">
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
    <!-- Revenue Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Revenue Summary
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped">
                    <tbody>
                        <tr>
                            <td>Net Sales</td>
                            <td class="text-right">{{ config('app.currency_symbol') }} {{ $total['total_before_tax']}}</td>
                        </tr>
                        <tr>
                            <td>Vat collected</td>
                            <td class="text-right">{{ config('app.currency_symbol') }} {{ $total['tax']}}</td>
                        </tr>
                        <tr>
                            <td>Discounts</td>
                            <td class="text-right">- {{ config('app.currency_symbol') }} {{ $total['discount']}}</td>
                        </tr>
                        <tr>
                            <td>Net Sales</td>
                            <td class="text-right">{{ config('app.currency_symbol') }} {{ $total['total']}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment recivied From Customer accounts -->
        <div class="card">
            <div class="card-header">
                Payment Received From Customer Accounts
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable-sales">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th class="text-right">Count</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerAccountSummary as $customer)
                            <tr>
                                <td>{{ $customer['name'] }}</td>
                                <td class="text-right">{{ $customer['count']}}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $customer['total']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Tax summary -->
        <div class="card">
            <div class="card-header">
                Tax Summary
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable-sales">
                    <thead>
                        <tr>
                            <th>Taxrate</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxSummary as $tax)
                            <tr>
                                <td>{{ $tax['tax_rate'] }}</td>
                                <td class="text-right">{{ $tax['quantity']}}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $tax['total']}}</td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales by category -->
        <div class="card">
            <div class="card-header">
                Sales by Category
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable-sales">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorySummary as $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td class="text-right">{{ $category['quantity']}}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $category['total']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Payments Summary -->
        <div class="card">
            <div class="card-header">
                Payment Summary
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable-sales">
                    <thead>
                        <tr>
                            <th>Paymnet Method</th>
                            <th class="text-right">Count</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentSummary as $payment)
                            <tr>
                                <td>{{ $payment['payment_method'] }}</td>
                                <td class="text-right">{{ $payment['count'] }}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $payment['total']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product sales -->
        <div class="card">
            <div class="card-header">
                Product Sales
            </div>
            <div class="card-body">
                <table class="table table-borderedless table-striped datatable-sales">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Qty sold</th>
                            <th class="text-right">Vat</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productSummary as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td class="text-right">{{ $product['quantity']}}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $product['tax']}}</td>
                                <td class="text-right">{{ config('app.currency_symbol') }} {{ $product['total']}}</td>
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
        $('.datatable-sales').each(function() {
            let columnCount = $(this).find('th').length;
            if (columnCount > 0) { // Ensure table has at least one column
                $(this).DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    order: [[columnCount - 1, 'desc']] // Last column dynamically
                });
            }
        });
    </script>
@stop