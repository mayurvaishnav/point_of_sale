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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <table class="table tale-striped table-responsive" id="cart-table">
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
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#productModal">Add misc</button>
                    </div>
                    <div class="form-group col-md-9">
                        <input type="text" name="search" class="form-control" id="searchInput"
                            placeholder="Search product">
                    </div>
                </div>
                <!-- product display -->

                <!-- Tab panel for categories -->
                <ul class="nav nav-tabs flex-wrap" id="productTabs" role="tablist">
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $category->id }}" data-toggle="tab" href="#category-{{ $category->id }}" role="tab" aria-controls="category-{{ $category->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>

                <!-- Tb content for categories -->
                <div class="tab-content" id="productTabsContent">
                    @foreach ($categories as $category)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="category-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                            <div class="row mt-3">
                                @foreach ($products->where('category_id', $category->id) as $product)
                                    <div class="col-md-4 mb-3">
                                        @php
                                            $isOutOfStock = $product->quantity <= 0;
                                        @endphp
                                        <button class="btn btn-outline-info btn-block btn-lg btn-large" 
                                            @if ($isOutOfStock) disabled @else onclick="addToCart({{ $product->id }})"@endif
                                        >
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

                <!-- Container for all products when searching -->
                <div id="allProductsContainer" class="d-none">
                    <div class="row mt-3">
                        @foreach ($products as $product)
                            <div class="col-md-4 mb-3 product-item">
                                <button class="btn btn-outline-info btn-block btn-lg btn-large"
                                    @if ($isOutOfStock) disabled @else onclick="addToCart({{ $product->id }})"@endif
                                >
                                    {{ $product->name }}</br>
                                    <span style="font-size: small;">Qty: {{ $product->quantity }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('pos.miscProduct-modal')

@endsection

@section('js')
    <script>

        // Search funcationality
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var query = $(this).val().toLowerCase();

                if (query) {
                    // Hide tab panel and show all products container
                    $('#productTabs').addClass('d-none');
                    $('#productTabsContent').addClass('d-none');
                    $('#allProductsContainer').removeClass('d-none');

                    // Filter products in all products container
                    $('#allProductsContainer .product-item').each(function() {
                        var productText = $(this).text().toLowerCase();
                        if (productText.includes(query)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    // Show tab panel and hide all products container
                    $('#productTabs').removeClass('d-none');
                    $('#productTabsContent').removeClass('d-none');
                    $('#allProductsContainer').addClass('d-none');
                }
            });
        });

        // Adding misc product
        $(document).ready(function () {
            $('#productForm').on('submit', function (event) {
                event.preventDefault(); // Prevent full page reload

                addToCart(this.product_id, this.product_name, this.price, this.tax_rate);
            });
        });


        // Store to Cart
        function addToCart(id, name, price, tax_rate) {
            $.ajax({
                url: "{{ route('cart.addToCart') }}",
                method: "POST",
                data: {
                    product_id: id,
                    customer_id: 2,
                    name: name,
                    price: price,
                    tax_rate: tax_rate,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (response) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Success',
                    //     text: 'Product added successfully!',
                    //     timer: 2000,
                    //     showConfirmButton: false
                    // });

                    console.log(response);

                    // Clear the cart table
                    $('#cart-table tbody').empty();

                    // Convert cartItems object to an array
                    const cartItems = Object.values(response.cartItems);

                    // Iterate over the cart items and create new rows
                    cartItems.forEach(cartItem => {
                        const newRow = `
                            <tr>
                                <td>${cartItem.name}</td>
                                <td>${cartItem.quantity}</td>
                                <td>${cartItem.price}</td>
                                <td>
                                    <form action="{{ route('cart.removeFromCart') }}" method="POST" style="display:inline">
                                        @csrf
                                        <input hidden name="cart_item_id" value="${cartItem.id}"/>
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        // Append the new row to the cart table
                        $('#cart-table tbody').append(newRow);
                    });
                    

                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong, Please try again.',
                    });
                }
            });
        }
    </script>
@stop

