@extends('adminlte::page')

@section('title', 'Products')
@section('content_header')
    {{-- <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>POS</h1>
            </div>
        </div>
    </div> --}}
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

    /* Make the card take up the full viewport height */
    .full-height-card {
        height: calc(100vh - 110px);
        overflow: auto; /* Allow scrolling within the card if needed */
    }


    .parent-div {
        height: 100%; /* Ensure the parent div takes up the full height */
    }

    .child-div {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        height: 100%; /* Make the child div take up the full height of the parent */
        overflow-y: auto; /* Allow vertical scrolling within the child div if needed */
        overflow-x: hidden; /* Prevent horizontal scrolling */
        padding: 0.5rem;
    }
</style>
@endsection

@section('content')

@include('layouts.alerts')

<div class="card full-height-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <!-- Customer select -->
                <div class="form-group">
                    <select class="form-control select2" name="customer_id" id="customerSelect">
                        <option>-- Select Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ ($cart->customer->id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
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
                                        <td>
                                            <input
                                                type="text"
                                                value={{ $item->price }}
                                                class="form-control input-number"
                                                {{-- onChange={(e) => updateCart(c.id, e.target.value)} --}}
                                            />
                                        </td>
                                        <td class="delete-column pl-0 pr-0">
                                            <button class="btn btn-danger btn-sm deleteFromCart" data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

                <div class="parent-div">
                    <div class="child-div">

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
    </div>
</div>

@include('pos.miscProduct-modal')

@endsection

@section('js')
    <script>

        
        $(document).ready(function() {

            // // Initialize Select2 for customers
            //     // Initialize Select2 on the customer select element
            //     $('#customerSelect').select2({
            //         placeholder: "-- Select Customer --",
            //         allowClear: true,
            //         // width: '100%',  // Ensure it takes full width
            //         // theme: 'bootstrap4' // Apply Bootstrap 4 styling
            //     });
            // });

            // Search funcationality
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

            // Adding misc product
            $('#productForm').on('submit', function (event) {
                event.preventDefault(); // Prevent full page reload

                const formData = {
                    product_name: $('#productForm input[name="product_name"]').val(),
                    customer_price: $('#productForm input[name="customer_price"]').val(),
                    tax_rate: $('#productForm select[name="tax_rate"]').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                addToCart(null, formData.product_name, formData.customer_price, formData.tax_rate);
            });

            // Delete from card
            $(document).on('click', '.deleteFromCart', function() {
                const cartItemId = $(this).data('id');

                // Add your AJAX request or other logic here to handle the deletion
                $.ajax({
                    url: "{{ route('cart.removeFromCart') }}",
                    method: "POST",
                    data: {
                        cart_item_id: cartItemId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Re-render the cart
                        reRenderCart(Object.values(response.cartItems));
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong, Please refresh and try again.',
                        });
                    }
                });
            });
        });



        // Store to Cart
        function addToCart(id, product_name, customer_price, tax_rate) {
            $.ajax({
                url: "{{ route('cart.addToCart') }}",
                method: "POST",
                data: {
                    product_id: id,
                    customer_id: 2,
                    product_name: product_name,
                    customer_price: customer_price,
                    tax_rate: tax_rate,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (response) {
                    // Close the modal
                    $('#productForm').trigger("reset");
                    $('#productModal').modal('hide');

                    // Re-render the cart
                    reRenderCart(Object.values(response.cartItems));
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong, Please refresh and try again.',
                    });
                }
            });
        }

        // Renender cart
        function reRenderCart(cartItems) {

            // Clear the cart table
            $('#cart-table tbody').empty();

            // Iterate over the cart items and create new rows
            cartItems.forEach(cartItem => {
                const newRow = `
                    <tr>
                        <td>${cartItem.name}</td>
                        <td>${cartItem.quantity}</td>
                        <td>${cartItem.price}</td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteFromCart" data-id="${cartItem.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                // Append the new row to the cart table
                $('#cart-table tbody').append(newRow);
            });
        }


        // Handle click event on quantity cell
        // $(document).on('click', '.quantity-cell', function () {
        //     const cell = $(this);
        //     const currentQuantity = cell.text();
        //     const cartItemId = cell.data('id');

        //     // Replace cell content with an input field
        //     cell.html(`<input type="number" class="form-control quantity-input input-number" value="${currentQuantity}" data-id="${cartItemId}">`);
        // });



        // Handle change event on quantity input field
        // $(document).on('blur', '.quantity-input', function () {
        //     const input = $(this);
        //     const newQuantity = input.val();
        //     const cartItemId = input.data('id');

        //     // Update the quantity in the cart (you can add an AJAX request here to update the server)
        //     // For now, just update the cell content
        //     input.parent().text(newQuantity);

        //     // Optionally, you can send an AJAX request to update the quantity on the server
        //     // $.ajax({
        //     //     url: "{{ route('cart.updateQuantity') }}",
        //     //     method: "POST",
        //     //     data: {
        //     //         cart_item_id: cartItemId,
        //     //         quantity: newQuantity,
        //     //         _token: $('meta[name="csrf-token"]').attr('content')
        //     //     },
        //     //     success: function (response) {
        //     //         // Handle success response
        //     //     },
        //     //     error: function (xhr) {
        //     //         // Handle error response
        //     //     }
        //     // });
        // });
    </script>
@stop

