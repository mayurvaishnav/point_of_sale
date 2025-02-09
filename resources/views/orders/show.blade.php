@extends('adminlte::page')

@section('title', 'Order Details')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice No: {{ $order->invoice_number }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                @if (!$order->customer)
                    <button class="btn btn-success" data-toggle="modal" data-target="#emailInvoiceModal">
                        <i class="fas fa-envelope"></i> Send Email
                    </button>
                    @include('orders.customer-email-modal')
                @else
                    <form action="{{ route('orders.emailInvoice', $order) }}" method="POST" style="display:inline" id="emailInvoiceForm" onsubmit="startLoadingEmail()">
                        @csrf
                        <button type="submit" class="btn btn-success" id="sendEmailButton">
                            <span id="buttonText"><i class="fa fa-envelope"></i> Send Email</span>
                            <span id="loadingSpinner" style="display: none;"><i class="fa fa-spinner fa-spin"></i> Sending...</span>
                        </button>
                    </form>
                @endif
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('print.receipt', $order->id) }}" id="printReceipt">Receipt</a>
                        <button class="dropdown-item" id="printA4" data-order-id={{ $order->id }}>A4 Invoice</button>
                    </div>
                </div>
                <a href="{{route('orders.downloadInvoice', $order)}}" class="btn btn-info" id="downloadButton">
                    <i class="fa fa-download"></i> Download
                </a>
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
        <div class="col-md-6">
            <div class="card mb-4 table-responsive">
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
                            <td><strong>Total:</strong></td>
                            <td>{{ $order->total }}</td>
                        </tr>
                        <tr>
                            <td><strong>Discount:</strong></td>
                            <td>{{ $order->discount }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total After Discount:</strong></td>
                            <td>{{ $order->total_after_discount }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Customer Details</h4>
                    @if ($order->canEditCustomer())
                        <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#editCustomerModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        @include('orders.edit-customer-modal')
                    @endif
                </div>
                <div class="card-body table-responsive">
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
                        <th>Subtotal</th>
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
<iframe id="pdf-frame" style="display:none;" width="100%" height="600"></iframe>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#editCustomerModal').on('shown.bs.modal', function () {
            $('#customerSelect').select2({
                dropdownParent: $('#editCustomerModal')
            });
        });

        
        // // Disable download button on click
        // $('#downloadButton').on('click', function(event) {
        //     var button = $(this);
        //     button.prop('disabled', true);
        //     button.html('<i class="fa fa-spinner fa-spin"></i> Downloading...');
        // });
    });

    $('#printA4').click(function () {
        var orderId = $(this).data('order-id'); 

        const url = `{{ route('orders.downloadInvoice', ':orderId') }}`;
        
        $.ajax({
            url: url.replace(':orderId', orderId),
            method: 'GET',
            data: { 
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {

                console.log('response', response);

                // If you want to display the PDF directly in an iframe and auto-print
                var iframe = document.getElementById('pdf-frame');
                var pdfBlob = new Blob([response], { type: 'application/pdf' });
                var pdfUrl = URL.createObjectURL(pdfBlob);
                iframe.src = pdfUrl;
                iframe.style.display = 'block';

                // Wait for the PDF to load, then print automatically
                iframe.onload = function () {
                    iframe.contentWindow.print();
                };
            },
            error: function (error) {
                console.error('Error generating invoice PDF:', error);
            }
        });
    });

    function startLoadingEmail() {
        let button = document.getElementById('sendEmailButton');
        let buttonText = document.getElementById('buttonText');
        let loadingSpinner = document.getElementById('loadingSpinner');

        // Disable button and change text
        button.disabled = true;
        buttonText.style.display = 'none'; 
        loadingSpinner.style.display = 'inline-block';
    }
</script>
@stop