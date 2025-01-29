@extends('adminlte::page')

@section('title', 'Products')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>POS</h1>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('content')

@include('layouts.alerts')
Cart


<div class="form-group">
    <label for="customer_id">Customers</label>
    <select class="form-control" name="customer_id" required>
        <option selected="">-- Select customers --</option>
        @foreach ($customers as $customer)
            <option value="{{ $customer->id }}" {{ old('customer_id', $cart->customer->id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
        @endforeach
    </select>
    @error('customer_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="products-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Product Price</th>
                    <th>total</th>
                    <th>tax(incl)</th>
                    <th>tax Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->cartItems as $item)
                <tr>
                    <td class="text-right">{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td class="text-right">{{$item->quantity}}</td>
                    <td class="text-right">{{ $item->price }}</td>
                    <td class="text-right">{{$item->total}}</td>
                    <td class="text-right">{{$item->tax}}</td>
                    <td class="text-right">{{$item->taxRate}}</td>
                    <td>
                        <form action="{{ route('cart.removeFromCart') }}" method="POST" style="display:inline">
                            @csrf
                            <input hidden name="cart_item_id" value="{{ $item->id }}"/>
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">Total</td>
                    <td class="text-right">{{ $cart->total->total ?? '' }}</td>
                    <td class="text-right">{{ $cart->total->tax ?? '' }}</td> 
                    <td class="text-right">{{ $cart->total->taxRate ?? '' }}</td>
                    <td></td>
                </tr> --}}
            </tbody>
        </table>

        TicketTotal = {{ $cart->getTotal()['total'] }} - {{ $cart->getTotalCart()->total }} </br>
        Discount = {{ $cart->getTotal()['discount'] }} - {{ $cart->getTotalCart()->discount }} </br>
        Tax = {{ $cart->getTotal()['tax'] }} - {{ $cart->getTotalCart()->tax }} </br>
        SubTotal = {{ $cart->getTotal()['subTotal'] }} - {{ $cart->getTotalCart()->subTotal }} </br>
        Total = {{ $cart->getTotal()['totalAfterDiscount'] }} - {{ $cart->getTotalCart()->totalAfterDiscount }} </br>




        <form action="{{ route('cart.empty') }}" method="POST" style="display:inline">
            @method('delete')
            @csrf
            <button class="btn btn-default">
                Void Cart
            </button>
        </form>

        <form action="{{ route('pos.pay') }}" method="POST" style="display:inline">
            @csrf
            <input type="hidden" name="payment_method" value="cash">
            <button class="btn btn-primary">
                Pay by Cash
            </button>
        </form>

        <form action="{{ route('pos.pay') }}" method="POST" style="display:inline">
            @csrf
            <input type="hidden" name="payment_method" value="card">
            <button class="btn btn-primary">
                Pay by Card
            </button>
        </form>

        <form action="{{ route('pos.pay') }}" method="POST" style="display:inline">
            @csrf
            <input type="hidden" name="payment_method" value="customer_credit">
            <button class="btn btn-primary">
                Pay by Customer Credit
            </button>
        </form>

        <form action="{{ route('pos.save') }}" method="POST" style="display:inline">
            @csrf
            <button class="btn btn-success">
                Save for later
            </button>
        </form>
    </div>
</div>


Products

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="products-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>taxRate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="text-right">{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td class="text-right">{{$product->quantity}}</td>
                    <td class="text-right">{{$product->tax_rate}}</td>
                    <td>
                        <form action="{{ route('cart.addToCart') }}" method="POST" style="display:inline">
                            @csrf
                            <input hidden name="product_id" value="{{ $product->id }}"/>
                            <input hidden name="customer_id" value="2"/>
                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-cart-plus"></i>
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
            $('#products-table').DataTable({
                pageLength: 50,
            });
        });

        $(".cart-add-item").click(function(e){
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

