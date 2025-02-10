@extends('layouts.adminlte-app')

@section('title', 'Orders')
@section('custom_content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer Accounts</h1>
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

@section('custom_js')
    <script>
        
    </script>
@stop

