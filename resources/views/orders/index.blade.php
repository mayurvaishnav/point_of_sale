@extends('adminlte::page')

@section('title', 'Orders')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Orders</h1>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('content')

@include('layouts.alerts')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="orders-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Method</th>
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
                    <td class="text-right">{{$order->total}}</td>
                    <td>{{$order->status}}</td>
                    <td>{{$order->paid_method}}</td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">Details</a>
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline">
                            @method('delete')
                            @csrf
                            <button class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                pageLength: 50,
                order: [[0, 'desc']]
            });
        });

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

