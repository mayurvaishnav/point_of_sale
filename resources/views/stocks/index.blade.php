@extends('adminlte::page')

@section('title', 'Stock Management')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Stock Management</h1>
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
                    <th>Id</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Price/qty</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="text-right">{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->category->name ?? ''}}</td>
                    <td>{{$product->supplier->name ?? ''}}</td>
                    <td class="text-right">€ {{$product->selling_price}}</td>
                    <td class="text-right">{{$product->quantity}}</td>
                    <td>
                        <span class="d-none">{{$product}}</span>
                        @can('stock-management-add-sctock')
                            <button class="btn btn-success btn-sm add-stock-btn" 
                                data-product-id="{{ $product->id }}"  
                                data-product-buying-price="{{ $product->buying_price }}"
                                data-product-name="{{ $product->name }}"
                            >Add Stock</button>
                        @endcan

                        @can('stock-management-adject')
                            <button class="btn btn-warning btn-sm adject-stock-btn"
                                data-product-id="{{ $product->id }}"  
                                data-product-quantity="{{ $product->quantity }}"
                                data-product-name="{{ $product->name }}"
                            >Adject</button>
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
        document.querySelectorAll('.add-stock-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productBuyingPrice = this.getAttribute('data-product-buying-price');
                const productName = this.getAttribute('data-product-name');
                Swal.fire({
                    title: `Add Stock for ${productName}`,
                    html: `
                        <form id="add-stock-form" action="{{ route('stocks.add', '') }}/${productId}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="${productId}">
                            <div class="form-group">
                                <label for="quantity">Quantity Recivied:</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="buying_price">Buying Price Per Quantity:</label>
                                <input type="number" id="buying_price" name="buying_price" value=${productBuyingPrice} class="form-control" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
                        const quantity = document.getElementById('quantity').value;
                        const buyingPrice = document.getElementById('buying_price').value;

                        if (!quantity || quantity < 0) {
                            Swal.showValidationMessage('Please enter a valid quantity');
                            return false;
                        }

                        if (!buyingPrice || buyingPrice < 0) {
                            Swal.showValidationMessage('Please enter a valid buying price');
                            return false;
                        }

                        document.getElementById('add-stock-form').submit();
                    }
                });
            });
        });

        document.querySelectorAll('.adject-stock-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productQuantity = this.getAttribute('data-product-quantity');
                const productName = this.getAttribute('data-product-name');
                Swal.fire({
                    title: `Add Stock for ${productName}`,
                    html: `
                        <form id="add-stock-form" action="{{ route('stocks.adject', '') }}/${productId}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="quantity">New Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="${productQuantity}" class="form-control" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
                        const quantity = document.getElementById('quantity').value;

                        if (!quantity || quantity < 0) {
                            Swal.showValidationMessage('Please enter a valid quantity');
                            return false;
                        }

                        document.getElementById('add-stock-form').submit();
                    }
                });
            });
        });
    </script>
@stop

