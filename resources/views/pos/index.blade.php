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
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="products-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Sub total</th>
                    <th>tax</th>
                    <th>tax Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $product)
                <tr>
                    <td class="text-right">{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td class="text-right">{{$product->quantity}}</td>
                    <td class="text-right">{{$product->price}}</td>
                    <td class="text-right">{{$product->tax}}</td>
                    <td class="text-right">{{$product->taxRate}}</td>
                    <td>
                        <form action="{{ route('cart.removeFromCart', $product->id) }}" method="GET" style="display:inline">
                            @csrf
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>



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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="text-right">{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td class="text-right">{{$product->quantity}}</td>
                    <td>
                        <form action="{{ route('cart.addToCart', $product->id) }}" method="GET" style="display:inline">
                            @csrf
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

