@extends('layouts.adminlte-app')

@section('title', 'Customer Report')
@section('custom_content_header')
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
                    <div class="form-group ml-2" style="width: 300px;">
                        <select class="form-control select2" name="customer_id" id="customerSelect" required>
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ ($currentCustomer->id ?? '') == $customer->id ? 'selected' : '' }}
                                    data-phone="{{ $customer->phone }}" data-email="{{ $customer->email }}" data-reg="{{ $customer->registration_no }}"
                                >{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary ml-2">Filter</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
@endsection

@section('custom_content')

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

@section('custom_js')
    <script>
        $(document).ready(function() {

            function matchCustom(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                // Match the term with the text, phone, email, or registration number
                var term = params.term.toLowerCase();
                var phone = $(data.element).data('phone') ? $(data.element).data('phone').toLowerCase() : '';
                var email = $(data.element).data('email') ? $(data.element).data('email').toLowerCase() : '';
                var registrationNo = $(data.element).data('reg') ? $(data.element).data('reg').toLowerCase() : '';

                if (data.text.toLowerCase().indexOf(term) > -1 || 
                    phone.indexOf(term) > -1 || 
                    email.indexOf(term) > -1 || 
                    registrationNo.indexOf(term) > -1) {
                    return data;
                }

                // Return `null` if the term should not be displayed
                return null;
            }

            // // Initialize Select2 for customers
            $('#customerSelect').select2({
                matcher: matchCustom
            });
        });
        
    </script>
@stop