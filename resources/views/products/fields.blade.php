<div class="row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            id="name"
            placeholder="Name" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="category_id">Category</label>
        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="categories" required>
            <option value="">-- Select Category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="is_active">Active: </label>
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id='is_active' value="1" {{ isset($product) && $product->is_active ? 'checked' : '' }}
            class="@error('is_active') is-invalid @enderror">
        @error('is_active')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-12">
        <label for="description">Description</label>
        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
            id="description"
            placeholder="Description">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="card col-md-12">
        <div class="card-header">
            Pricing Information
        </div>
        <div class="card-body row">

            <div class="form-group col-md-6">
                <label for="tax_rate_id">Tax rate</label>
                <select name="tax_rate_id" class="form-control @error('tax_rate_id') is-invalid @enderror"" id="tax_rate" required>
                    <option value="">-- Select Taxrate --</option>
                    @foreach ($taxRates as $rate)
                        <option value="{{ $rate->id }}" data-rate="{{ $rate->value }}"
                            {{ old('tax_rate_id', $product->tax_rate_id ?? '') == $rate->id ? 'selected' : '' }}
                        >{{ $rate->name }}</option>
                    @endforeach
                </select>
                @error('tax_rate_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                    id="price"
                    placeholder="Selling Price" value="{{ old('price', $product->price ?? '') }}" required>
                @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label>Tax Included in price?</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="taxIncludedYes" name="tax_included" class="custom-control-input @error('tax_included') is-invalid @enderror" value="1" checked>
                        <label class="custom-control-label" for="taxIncludedYes">Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="taxIncludedNo" name="tax_included" class="custom-control-input @error('tax_included') is-invalid @enderror" value="0">
                        <label class="custom-control-label" for="taxIncludedNo">No</label>
                    </div>
                </div>
                @error('tax_included')
                <div class="invalid-feedback d-block">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label for="selling_price">Price to customer</label>
                <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                    id="selling_price"
                    placeholder="Selling Price" value="{{ old('selling_price', $product->selling_price ?? '') }}" readonly>
                @error('selling_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

        </div>
    </div>

    <div class="form-group col-md-12">
        <label>Is product stockable?</label>
        <div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="stockableYes" name="stockable" class="custom-control-input @error('stockable') is-invalid @enderror" value="1"
                    {{ old('stockable', $product->stockable ?? '') == 1 ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="stockableYes">Yes</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="stockableNo" name="stockable" class="custom-control-input @error('stockable') is-invalid @enderror" value="0"
                    {{ old('stockable', $product->stockable ?? '') == 0 ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="stockableNo">No</label>
            </div>
        </div>
        @error('stockable')
            <div class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="card col-md-12" id="stockInformation" style="display: none;">
        <div class="card-header">
            Stock Information
        </div>
        <div class="card-body row">
            <div class="form-group col-md-12">
                <label for="supplier_id">Supplier</label>
                <select class="form-control" name="supplier_id" id="suppliers">
                    <option value="">-- Select Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="form-group col-md-6">
                <label for="code">Product Code</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                    id="code"
                    placeholder="code" value="{{ old('code', $product->code ?? '') }}">
                @error('code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="garage">Garage</label>
                <input type="text" name="garage" class="form-control @error('garage') is-invalid @enderror"
                    id="garage"
                    placeholder="Garage" value="{{ old('garage', $product->garage ?? '') }}">
                @error('garage')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="store">Paul's Store</label>
                <input type="text" name="store" class="form-control @error('store') is-invalid @enderror"
                    id="store"
                    placeholder="Store" value="{{ old('store', $product->store ?? '') }}">
                @error('store')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="buying_price">Buying Price</label>
                <input type="number" step="0.01" name="buying_price" class="form-control @error('buying_price') is-invalid @enderror"
                    id="buying_price"
                    placeholder="Buying Price" value="{{ old('buying_price', $product->buying_price ?? '') }}">
                @error('buying_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                    placeholder="quantity" value="{{ old('quantity', $product->quantity ?? '') }}">
                @error('quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="brand">Brand</label>
                <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                    id="brand"
                    placeholder="Brand" value="{{ old('brand', $product->brand ?? '') }}">
                @error('brand')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label>Automatically order on low stock?</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="auto_order_at_low_stock_yes" name="auto_order_at_low_stock" class="custom-control-input @error('auto_order_at_low_stock') is-invalid @enderror" value="1"
                            {{ old('auto_order_at_low_stock', $product->auto_order_at_low_stock ?? '') == 1 ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="auto_order_at_low_stock_yes">Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="auto_order_at_low_stock_no" name="auto_order_at_low_stock" class="custom-control-input @error('auto_order_at_low_stock') is-invalid @enderror" value="0"
                            {{ old('auto_order_at_low_stock', $product->auto_order_at_low_stock ?? '') == 0 ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="auto_order_at_low_stock_no">No</label>
                    </div>
                </div>
                @error('auto_order_at_low_stock')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="low_stock_threshold">Low stock threshold</label>
                <input type="number" name="low_stock_threshold" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold"
                    placeholder="Low stock threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? '') }}">
                @error('low_stock_threshold')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="new_order_quantity">New quantity to order</label>
                <input type="number" name="new_order_quantity" class="form-control @error('new_order_quantity') is-invalid @enderror" id="new_order_quantity"
                    placeholder="Low stock threshold" value="{{ old('new_order_quantity', $product->new_order_quantity ?? '') }}">
                @error('new_order_quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

        </div>
    </div>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('products.index') }}">Cancel</a>
