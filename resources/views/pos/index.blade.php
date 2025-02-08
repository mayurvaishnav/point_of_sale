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
    /* product button */
    .product-button {
        width: 100%;
        text-align: left;
        white-space: normal; /* Allow text to wrap */
    }
    .product-info {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; /* Prevent text from wrapping */
    }
    .product-quantity {
        font-size: small;
        display: block;
        text-align: center;
    }
    @media (max-width: 576px) {
        .product-info {
            white-space: normal; /* Allow text to wrap on small screens */
            font-size: 0.875rem;
        }
    }
    .nav-link.active {
        background-color: #007bff; /* Bootstrap primary color */
        color: #fff; /* White text color */
    }
    .btn-large {
        padding: 20px 40px;
        font-size: 1.5rem;
        height: 100px;
    }
    .nav-tabs .nav-link.active {
        order: 1 !important;
        background-color: #007bff; /* Change this to your desired background color */
        color: #fff; /* Optional: Change text color to white */
    }
    /* .nav-tabs .nav-link.active {
    } */

    /* POS cart table */
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

    .input-number {
        padding-right: 0;
        padding-left: 0.3rem;
    }


    .scrollable-card {
        overflow: auto;
        max-height: 90vh;
    }

    /* Make the card take up the full viewport height */
    .full-height-card {
        height: calc(100vh - 110px);
        overflow: auto; /* Allow scrolling within the card if needed */
    }

</style>
@endsection

@section('content')

@include('layouts.alerts')

<div class="card full-height-card">
    <div class="card-body">
        <div class="row h-100">
            <div class="col-md-6 col-lg-4 h-100">
                <div class="card h-100">
                    <div class="card-header p-0">
                        <!-- Customer select -->
                        <div class="form-group">
                            <select class="form-control select2" name="customer_id" id="customerSelect">
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ ($cart->customer->id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body scrollable-card p-0">
                        <table class="table tale-striped table-responsive" id="cart-table">
                            <thead>
                                <tr>
                                    <th class="name-column">Name</th>
                                    <th class="qty-column">Qty</th>
                                    <th class="price-column">Price</th>
                                    <th class="total-column">Total</th>
                                    <th class="delete-column"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart->cartItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->total }}</td>
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
                    <div class="card-footer">
                        <div class="row mb-2">
                            <table class="col-md-12">
                                <tbody>
                                    <tr>
                                        <td>SubTotal</td>
                                        <td class="text-right" id="subTotal">{{ $cart->getTotalCart()->subTotal }}</td>
                                    </tr>
                                    <tr>
                                        <td>Vat</td>
                                        <td class="text-right" id="vat">{{ $cart->getTotalCart()->tax }}</td>
                                    </tr>
                                    @if ($cart->getTotalCart()->discount > 0)
                                        <tr>
                                            <td>Discount</td>
                                            <td class="text-right" id="discount">- {{ $cart->getTotalCart()->discount }}</td>
                                        </tr>
                                    @endif
                                    <tr class="text-danger">
                                        <td>Balance</td>
                                        <td class="text-right" id="balance">{{ $cart->getTotalCart()->totalAfterDiscount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-default btn-block" id="cancle-button">Cancel</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-info btn-block" id="layaway-button">Layaway</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success btn-block" id="settle-button" data-toggle="modal" data-target="#paymentModal">Settle</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-md-6 col-lg-8 h-100">
                <div class="card h-100">
                    <div class="card-header">
                        <!-- Search input -->
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#productModal">Add misc</button>
                            </div>
                            <div class="form-group col-md-9">
                                <input type="text" name="search" class="form-control" id="searchInput"
                                    placeholder="Search product">
                            </div>
                        </div>
                    </div>

                    <!-- product display -->
                    <div class="card-body scrollable-card">
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
                                                    $isOutOfStock = $product->stockable ? $product->quantity <= 0 : false;
                                                @endphp
                                                <button class="btn btn-outline-info btn-block btn-lg btn-large product-button" 
                                                    @if ($isOutOfStock) disabled @else onclick="addToCart({{ $product->id }})"@endif
                                                >
                                                    <div class="product-info">
                                                        @if ($isOutOfStock)
                                                                <i class="fas fa-exclamation-triangle text-danger"></i> 
                                                        @endif
                                                        <span class="d-none">{{$product->description}}</span>
                                                        {{ $product->name }}</br>
                                                        @if ($product->stockable)
                                                            <span class="product-quantity">Qty: {{ $product->quantity }}</span>
                                                        @endif
                                                    </div>
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
                                        @php
                                            $isOutOfStock = $product->stockable ? $product->quantity <= 0 : false;
                                        @endphp
                                        <button class="btn btn-outline-info btn-block btn-lg btn-large product-button"
                                            @if ($isOutOfStock) disabled @else onclick="addToCart({{ $product->id }})"@endif
                                        >
                                            <div class="product-info">
                                                <span class="d-none">{{$product->description}}</span>
                                                {{ $product->name }}</br>
                                                @if ($product->stockable)
                                                    <span class="product-quantity">Qty: {{ $product->quantity }}</span>
                                                @endif
                                            </div>
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
@include('pos.payment-modal')

@endsection

@section('js')
    <script>

        
        $(document).ready(function() {

            // // Initialize Select2 for customers
            $('#customerSelect').select2();

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
                        reRenderCart(response);
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

            // Update Customer selection
            $('#customerSelect').on('change', function() {
                const customerId = $(this).val();

                // Add your AJAX request or other logic here to handle the customer change
                $.ajax({
                    url: "{{ route('cart.updateCustomer') }}",
                    method: "POST",
                    data: {
                        customer_id: customerId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (response) {
                        // nothing to do here
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

            // Cancel button
            $('#cancle-button').on('click', function() {
                Swal.fire({
                    title: 'Cancel',
                    text: 'Are you sure you want to clear this cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    console.log(result);
                    if (result.value) {
                        console.log('cancled');
                        $.ajax({
                            url: "{{ route('cart.empty') }}",
                            method: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                // Re-render the cart
                                reRenderCart(response);
                                // Re-load the page
                                location.reload();
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
                });
                
            });

            // Layaway button
            $('#layaway-button').on('click', function() {
                Swal.fire({
                    title: 'Layaway',
                    text: 'Are you sure you want to layaway this cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('pos.save') }}",
                            method: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                // Reload the page
                                location.reload();
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
                });
            });

            // Settle button
            $('.payment-option').click(function() {
                let paymentMethod = $(this).data('method');
                let amountPaid = $('#amountPaid').val();
                let discountAmount = $('#discountAmount').val();
                
                $.ajax({
                    url: '{{ route('pos.processPayment') }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        payment_method: paymentMethod,
                        amount_paid: amountPaid,
                        discount_amount: discountAmount
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#paymentModal').modal('hide');

                        // refresh the page
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (let field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    errorMessages += errors[field].join('<br>') + '<br>';
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong, Please refresh and try again.',
                            });
                        }
                    }
                });
            });
        });



        // Store to Cart
        function addToCart(id, product_name, customer_price, tax_rate) {
            $customer_id = $('#customerSelect').val();
            $.ajax({
                url: "{{ route('cart.addToCart') }}",
                method: "POST",
                data: {
                    product_id: id,
                    customer_id: $customer_id,
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
                    reRenderCart(response);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessages,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong, Please refresh and try again.',
                        });
                    }
                }
            });
        }

        // Renender cart
        function reRenderCart(response) {

            // Update the customer select
            if (response.customer) {
                $('#customerSelect').val(response.customer.id).trigger('change');
            } else {
                $('#customerSelect').val('').trigger('change');
            }

            // Clear the cart table
            $('#cart-table tbody').empty();

            cartItems = Object.values(response.cartItems)

            // Iterate over the cart items and create new rows
            cartItems.forEach(cartItem => {
                const newRow = `
                    <tr>
                        <td>${cartItem.name}</td>
                        <td>${cartItem.quantity}</td>
                        <td>${cartItem.price}</td>
                        <td>${cartItem.total}</td>
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
            console.log("Now updating ..............");
            updateCartValues(response);
            console.log("Now updating Done ..............");
        }

        // Update cart values
        function updateCartValues(cart) {
            console.log(cart);
            $('#subTotal').text(cart.total.subTotal);
            $('#vat').text(cart.total.tax);
            if (cart.discount > 0) {
                $('#discount').text('- ' + cart.total.discount);
            }
            $('#balance').text(cart.total.totalAfterDiscount);
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

