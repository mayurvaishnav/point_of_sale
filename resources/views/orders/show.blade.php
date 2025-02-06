@extends('adminlte::page')

@section('title', 'Order Details')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice No: {{ $order->invoice_number }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.create')}}" class="btn btn-primary"><i class="fa fa-envolop"></i> Send email</a>
                <a href="{{route('products.create')}}" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                <a href="{{route('products.create')}}" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('content')

<div class="card">
    <div class="card-body">
    <div class="row">

        <!-- Order Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Order Information</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Order Id:</strong></td>
                            <td>#{{ $order->id }}</td>
                        </tr>                        <tr>
                            <td><strong>Invoice No:</strong></td>
                            <td>{{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>{{ $order->created_at->format('d-m-Y h:i:s A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                               {!! $order->getStatusBadge() !!}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>{{ $order->total_before_tax }}</td>
                        </tr>
                        <tr>
                            <td><strong>Vat:</strong></td>
                            <td>{{ $order->tax }}</td>
                        </tr>
                        <tr>
                            <td><strong>Discount:</strong></td>
                            <td>{{ $order->discount }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td>{{ $order->total_after_discount }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Details -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Customer Details</h4>
                    <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#editCustomerModal">
                        <i class="fas fa-edit"></i>
                    </button>
                    @include('orders.edit-customer-modal')
                </div>
                <div class="card-body">
                    @if ($order->customer == null)
                        <p>Customer not found.</p>
                    @else
                        <table class="table">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $order->customer->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $order->customer->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $order->customer->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $order->customer->address }}</td>
                            </tr>
                            <tr>
                                <td><strong>Car Brand:</strong></td>
                                <td>{{ $order->customer->brand }}</td>
                            </tr>
                            <tr>
                                <td><strong>Car Model:</strong></td>
                                <td>{{ $order->customer->model }}</td>
                            </tr>
                            <tr>
                                <td><strong>Registration No:</strong></td>
                                <td>{{ $order->customer->registration_no }}</td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Order Items</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="orders-details-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Unit price</th>
                        <th>Qty</th>
                        <th>Vat (included)</th>
                        <th>tax rate(%)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $detail->product_name }}</td>
                        <td>{{ $detail->unit_price }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->tax }}</td>
                        <td>{{ $detail->tax_rate }}%</td>
                        <td>{{ $detail->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Payment Details</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="payments-details-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Payment Method</th>
                        <th>Amount Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Date time</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $j = 0;
                    @endphp
                    @foreach ($order->orderPayments as $payment)
                    <tr>
                        <td>{{ ++$j }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->amount_paid }}</td>
                        <td>{{ $payment->amount_due }}</td>
                        <td>{!! $payment->getPaymnetStatusBadge() !!}</td>
                        <td>{{ $payment->created_at->format('d-m-Y h:i:s A') }}</td>
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
        $('#editCustomerModal').on('shown.bs.modal', function () {
            $('#customerSelect').select2({
                dropdownParent: $('#editCustomerModal')
            });
        });
    });
</script>
@stop