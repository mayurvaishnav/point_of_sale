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
<style>
    .name-column {
        width: 45%;
        word-wrap: break-word;
    }

    .qty-column {
        width: 20%;
    }

    .price-column {
        width: 25%;
    }

    .delete-column {
        width: 10%;
        vertical-align: middle !important;
    }

    .no-padding {
        padding: 0;
    }

    .input-number {
        padding-right: 0;
        padding-left: 0.3rem;
    }
    .scrollable-card {
        overflow: auto;
        max-height: 90vh;
    }
    .btn-large {
        padding: 20px 40px;
        font-size: 1.5rem;
        height: 100px;
    }
    .nav-tabs .nav-link.active {
        order: 1 !important;
    }
</style>
@endsection

@section('content')

@include('layouts.alerts')
Cart

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <!-- Customer select -->
                <div class="form-group">
                    <select class="form-control" name="customer_id" required>
                        <option selected="">-- Select Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ ($cart->customer_id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="card scrollable-card">
                        <table class="table tale-striped table-responsive">
                            <thead>
                                <tr>
                                    <th class="name-column">Name</th>
                                    <th class="qty-column">Qty</th>
                                    <th class="price-column">Price</th>
                                    <th class="delete-column"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart->cartItems as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            <input
                                                type="number" step="1" min="1"
                                                value={{ $item->quantity }}
                                                class="form-control input-number"
                                                {{-- onChange={(e) => updateCart(c.id, e.target.value)} --}}
                                            />
                                        </td>
                                        <td class="">
                                            <input
                                                type="text"
                                                value={{ $item->price }}
                                                class="form-control input-number"
                                                {{-- onChange={(e) => updateCart(c.id, e.target.value)} --}}
                                            />
                                        </td>
                                        <td class="delete-column pl-0 pr-0">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8">
                <!-- Search input -->
                <div class="form-group">
                    <input type="text" name="search" class="form-control" id="name"
                        placeholder="Search product">
                </div>
                <!-- product display -->
                <ul class="nav nav-tabs flex-wrap" id="productTabs" role="tablist">
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $category->id }}" data-toggle="tab" href="#category-{{ $category->id }}" role="tab" aria-controls="category-{{ $category->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="productTabsContent">
                    @foreach ($categories as $category)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="category-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                            <div class="row mt-3">
                                @foreach ($products->where('category_id', $category->id) as $product)
                                    <div class="col-md-4 mb-3">
                                        @php
                                            $isOutOfStock = $product->quantity >= 0;
                                        @endphp
                                        <button class="btn btn-outline-info btn-block btn-lg btn-large" @if ($isOutOfStock) disabled @else onclick="addToCart({{ $product->id }})"@endif>
                                            @if ($isOutOfStock)
                                                    <i class="fas fa-exclamation-triangle text-danger"></i> 
                                            @endif
                                            {{ $product->name }}</br>
                                            <span style="font-size: small;">Qty: {{ $product->quantity }}</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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

