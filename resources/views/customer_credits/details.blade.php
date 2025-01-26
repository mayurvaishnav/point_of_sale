@extends('adminlte::page')

@section('title', 'Orders')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $customer->name }}</h1>
                <p>
                    {{ $customer->address }}<br>
                    {{ $customer->email }} <br>
                    {{ $customer->phone }}
                </p>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary add-customer-credit-btn"><i class="fa fa-plus"></i> Add Paymnet</a>
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
                @foreach ($customerCredits as $customerCredit)
                <tr>
                    <td>{{$customerCredit->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if ($customerCredit->order)
                            <a href="{{ route('orders.show', $customerCredit->order) }}">
                                {{$customerCredit->order->invoice_number}}
                            </a>
                        @endif
                    </td>
                    <td>{{$customerCredit->note}}</td>
                    <td class="text-right">{{$customerCredit->credit_amount }}</td>
                    <td class="text-right">{{$customerCredit->paid_amount }}</td>
                    <td class="text-right">{{$customerCredit->balance }}</td>
                    <td>
                        @if ($customerCredits->first()->id == $customerCredit->id && $customerCredit->paid_amount != null)
                            <form action="{{ route('customer-credits.deletePayment', ['customer' => $customer, 'customerCredit' => $customerCredit]) }}" method="POST" style="display:inline">
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

@section('js')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                pageLength: 50,
            });
        });
        
        document.querySelectorAll('.add-customer-credit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const customerName = @json($customer->name);
                const customerId = @json($customer->id);
                Swal.fire({
                    title: `Add payment for ${customerName}`,
                    html: `
                        <form id="add-payment-form" action="{{ route('customer-credits.addPayment', '') }}/${customerId}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="note">Note:</label>
                                <input type="text" id="note" name="note" class="form-control">
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

