@extends('adminlte::page')

@section('title', 'Order Details')
@section('content_header')
    <h1>Order Detail</h1>
@stop

@section('css')

@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered" id="orders-details-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Unit price</th>
                        <th>Qty</th>
                        <th>Vat (included)</th>
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
                        <td>{{ $detail->unit_cost }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->tax }}</td>
                        <td>{{ $detail->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            
        </div>
    </div>
@endsection

@section('js')

@stop