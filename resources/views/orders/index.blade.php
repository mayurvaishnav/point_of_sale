@extends('layouts.adminlte-app')

@section('title', 'Orders')
@section('custom_content_header')
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
                            <input type="date" name="order_date" id="orderDate" class="form-control" value="{{ $date ?? request('order_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary ml-2">Filter</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
@endsection

@section('custom_content')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td class="text-right">{{$order->id}}</td>
                    <td>{{$order->invoice_number}}</td>
                    <td>{{$order->customer->name ?? ''}}</td>
                    <td>{{$order->order_date }}</td>
                    <td>{!! $order->getStatusBadge() !!}</td>
                    <td>{{ $order->orderPayments->first()->payment_method ?? '' }}</td>
                    <td class="text-right">{{$order->total_after_discount}}</td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">Details</a>
                        @if($order->canBeDeleted())
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
    <script>
        $(".btn-delete").click(function(e){
            e.preventDefault();
            var form = $(this).parents("form");

            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.value) {
                form.submit();
            }
            });

        });
    </script>
@stop

