@extends('adminlte::page')

@section('title', 'Orders')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $customerAccount->customer->name }}</h1>
                <p>
                    {{ $customerAccount->customer->address }}<br>
                    {{ $customerAccount->customer->email }} <br>
                    {{ $customerAccount->customer->phone }}
                </p>
            </div>
            <div class="col-sm-6 text-right">
                @can('customer-account-add-payment')
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus"></i> Add Payment
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item add-payment-option" data-payment-method="Paid by cash" href="#">Pay with Cash</a>
                            <a class="dropdown-item add-payment-option" data-payment-method="Paid by card" href="#">Pay with Card</a>
                            <a class="dropdown-item add-payment-option" data-payment-method="Paid by bank transfer" href="#">Pay with Bank Transfer</a>
                        </div>
                    </div>
                @endcan
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
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th>Note</th>
                    <th>Credit</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerAccount->transactions as $transaction)
                <tr>
                    <td>{{$transaction->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if ($transaction->order)
                            <a href="{{ route('orders.show', $transaction->order) }}">
                                {{$transaction->order->invoice_number}}
                            </a>
                        @endif
                    </td>
                    <td>{{$transaction->note}}</td>
                    <td class="text-right">{{$transaction->credit_amount }}</td>
                    <td class="text-right">{{$transaction->paid_amount }}</td>
                    <td class="text-right">{{$transaction->balance }}</td>
                    <td>
                        @can('customer-account-delete-payment')
                            @if ($customerAccount->transactions->first()->id == $transaction->id && $transaction->paid_amount != null)
                                <form action="{{ route('customer-accounts.deletePayment', ['customerAccount' => $customerAccount, 'customerAccountTransaction' => $transaction]) }}" method="POST" style="display:inline">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
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
        document.querySelectorAll('.add-payment-option').forEach(button => {
            button.addEventListener('click', function() {
                const customerAccountId = @json($customerAccount->id);
                const customerName = @json($customerAccount->customer->name);
                const customerId = @json($customerAccount->customer->id);
                const paymentMethod = this.dataset.paymentMethod;
                Swal.fire({
                    title: `Add payment for ${customerName}`,
                    html: `
                        <form id="add-payment-form" action="{{ route('customer-accounts.addPayment', '') }}/${customerAccountId}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="note">Note:</label>
                                <input type="text" id="note" name="note" class="form-control" value="${paymentMethod}">
                            </div>
                            <div class="form-group">
                                <label for="amount">Payment amount:</label>
                                <input type="number" id="amount" name="amount" class="form-control" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
                        document.getElementById('add-payment-form').submit();
                    }
                });
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

