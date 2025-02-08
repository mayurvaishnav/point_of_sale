@extends('adminlte::page')

@section('title', 'Orders')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer Accounts</h1>
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
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Account Id</th>
                    <th>Customer Name</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerAccounts as $account)
                <tr>
                    <td class="text-right">{{$account->id}}</td>
                    <td>{{$account->customer->name}}</td>
                    <td class="text-right">{{$account->transactions->first()->balance}}</td>
                    <td>
                        @can('customer-account-details')
                            <a href="{{ route('customer-accounts.details', $account) }}" class="btn btn-success btn-sm">Show Details</i></a>
                        @endcan
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
        
    </script>
@stop

