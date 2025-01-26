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
                    <th>Customer</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr>
                    <td class="text-right">{{$customer->id}}</td>
                    <td>{{$customer->name}}</td>
                    <td class="text-right">{{$customer->customerCredits->first()->balance}}</td>
                    <td>
                        <a href="{{ route('customer-credits.details', $customer) }}" class="btn btn-success btn-sm">Show Details</i></a>
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
            });
        });

    </script>
@stop

