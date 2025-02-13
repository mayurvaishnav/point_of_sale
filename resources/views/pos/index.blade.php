@extends('layouts.adminlte-app')

@section('title', 'Products')
@section('custom_content_header')
    {{-- <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>POS</h1>
            </div>
        </div>
    </div> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('custom_css')
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
    .cart-item {
        cursor: pointer;
    }
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

    .receipt-modal-dialog {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        max-width: 100%;
    }

    .receipt-modal-content {
        height: 100vh;
        border-radius: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

</style>
@endsection

@section('custom_content')

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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart->cartItems as $item)
                                    <tr class="cart-item" 
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-quantity="{{ $item->quantity }}"
                                        data-price="{{ $item->price }}"
                                        data-total="{{ $item->total }}"
                                    >
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->total }}</td>
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
                                <button class="btn btn-info btn-block" id="layaway-button" {{ empty($cart->cartItems) ? 'disabled' : '' }}>Layaway</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success btn-block" id="settle-button" data-toggle="modal" data-target="#paymentModal" {{ empty($cart->cartItems) ? 'disabled' : '' }}>Settle</button>
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
@include('pos.itemUpdate-modal')
@include('pos.paymentReceipt-modal')
<iframe id="pdf-frame" style="display:none;" width="100%" height="1"></iframe>

@endsection

@section('custom_js')
    <script>

        
        $(document).ready(function() {

            // // Initialize Select2 for customers
            $('#customerSelect').select2();

            // Update the total amount in the payment modal
            $('#paymentModal').on('shown.bs.modal', function () {
                calculateTotalPrice();
            });

            // Open modal when clicking on a cart row
            $(document).on("click", ".cart-item", function() {
                let itemId = $(this).data("id");
                let itemName = $(this).data("name");
                let itemQuantity = $(this).data("quantity");
                let itemPrice = $(this).data("price");
                let itemTotal = $(this).data("total");

                $("#editItemId").val(itemId);
                $("#editItemName").val(itemName);
                $("#editItemQuantity").val(itemQuantity);
                $("#editItemPrice").val(itemPrice);
                $("#editTotalPrice").val(itemTotal);

                // Select the delete button *inside the modal* and set its data-id correctly
                $("#deleteFromCart").attr("data-id", itemId);

                if (itemId > 0) {
                    $("#editItemName").attr("readonly", true);
                } else {
                    $("#editItemName").attr("readonly", false);
                }

                $("#editItemModal").modal("show");
            });

            // Handle update button click
            $("#updateItem").on("click", function () {
                let itemId = $("#editItemId").val();
                let newQuantity = $("#editItemQuantity").val();
                let newPrice = $("#editItemPrice").val();
                let newName = $("#editItemName").val();

                $.ajax({
                    url: "{{ route('cart.update') }}",
                    type: "POST",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                        item_id: itemId,
                        quantity: newQuantity,
                        price: newPrice,
                        name: newName,
                    },
                    success: function (response) {
                        // Close the modal
                        $('#editItemModal').modal('hide');

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

            // Delete from cart
            $('#deleteFromCart').on('click', function() {
                const cartItemId = $(this).attr('data-id');

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
                        // Close the modal
                        $('#editItemModal').modal('hide');

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

            // Update Customer selection
            $('#customerSelect').on('change', function() {
                if ($(this).val()) {
                    $('#customerAccountPay').show();
                } else {
                    $('#customerAccountPay').hide();
                }
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
                    if (result.value) {
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
                const paymentMethod = $(this).data('method');
                const amountPaid = $('#amountPaid').val();
                const discountAmount = $('#discountAmount').val();
                const note = $('#orderNote').val();
                
                $.ajax({
                    url: '{{ route('pos.processPayment') }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        payment_method: paymentMethod,
                        amount_paid: amountPaid,
                        discount_amount: discountAmount,
                        note: note
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#paymentModal').modal('hide');

                        const orderId = response.order_id;

                        // Update data-od of all buttons
                        $("#printReceipt").attr("data-id", orderId);
                        $("#printA4").attr("data-id", orderId);
                        $("#downloadInvoice").attr("data-id", orderId);
                        

                        if (response.customer_id) {
                            $("#emailInvoice").attr("data-id", orderId);
                            $("#emailInvoice").show();
                        } else {
                            $("#emailInvoice").hide();
                        }

                        // Show the payment receipt modal
                        $('#paymentReceiptModal').modal('show');

                        // refresh the page
                        // location.reload();
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

            // Print receipt
            $('#printReceipt').click(function() {
                const orderId = $(this).data('id');
            });

            // Print A4
            $('#printA4').click(function() {
                const orderId = $(this).data('id');
                const url = `{{ route('orders.downloadInvoice', ':orderId') }}`.replace(':orderId', orderId);
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { _token: "{{ csrf_token() }}" },
                    xhrFields: { responseType: 'blob' },
                    success: function (response) {
                        // Convert the response into a Blob
                        var pdfBlob = new Blob([response], { type: 'application/pdf' });
                        var pdfUrl = URL.createObjectURL(pdfBlob);

                        // Set the PDF URL to the iframe
                        var iframe = document.getElementById('pdf-frame');
                        iframe.src = pdfUrl;
                        iframe.style.display = 'block';

                        // Print automatically once loaded
                        iframe.onload = function () {
                            iframe.contentWindow.print();

                            setTimeout(function () {
                                iframe.style.display = 'none';
                            }, 1000);
                        };

                        // Re-load the page
                        // location.reload();
                    },
                    error: function (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong, Please try again printing from orders page.',
                        });
                    }
                });
            });

            // Download invoice
            $('#downloadInvoice').click(function() {
                const orderId = $(this).data('id');
                const url = `{{ route('orders.downloadInvoice', ':orderId') }}`;
                window.open(url.replace(':orderId', orderId), '_blank');

                // reload the page
                location.reload();
            });

            // Email invoice
            $('#emailInvoice').click(function() {
                const orderId = $(this).data('id');
                const url = `{{ route('orders.emailInvoice', ':orderId') }}`;

                // disable the button
                $(this).attr('disabled', true);

                $.ajax({
                    url: url.replace(':orderId', orderId),
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Re-load the page
                        location.reload();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong, Please try again sending the email from orders page.',
                        });
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
                $('#customerSelect').val(response.customer.id);
                $('#customerAccountPay').show();
            } else {
                $('#customerSelect').val('');
                $('#customerAccountPay').hide();
            }

            // Clear the cart table
            $('#cart-table tbody').empty();

            cartItems = Object.values(response.cartItems)

            // Iterate over the cart items and create new rows
            cartItems.forEach(cartItem => {
                const newRow = `
                    <tr class="cart-item" 
                        data-id="${cartItem.id}"
                        data-name="${cartItem.name}"
                        data-quantity="${cartItem.quantity}"
                        data-price="${cartItem.price}"
                        data-total="${cartItem.total}"
                    >
                        <td>${cartItem.name}</td>
                        <td>${cartItem.quantity}</td>
                        <td>${cartItem.price}</td>
                        <td>${cartItem.total}</td>
                    </tr>
                `;

                // Append the new row to the cart table
                $('#cart-table tbody').append(newRow);
            });
            // Update cart values and buttons
            updateCartValues(response);
        }

        // Update cart values
        function updateCartValues(cart) {
            $('#subTotal').text(cart.total.subTotal);
            $('#vat').text(cart.total.tax);
            if (cart.discount > 0) {
                $('#discount').text('- ' + cart.total.discount);
            }
            $('#balance').text(cart.total.totalAfterDiscount);

            // Disable buttons if cart is empty
            if(cart.total.subTotal == 0){
                $('#layaway-button').attr('disabled', true);
                $('#settle-button').attr('disabled', true);
            } else {
                $('#layaway-button').attr('disabled', false);
                $('#settle-button').attr('disabled', false);
            }

            // Update the total amount in the payment modal
            $('#totalAmount').val(cart.total.total).trigger('change');
            calculateTotalPrice();
        }
    </script>
@stop

