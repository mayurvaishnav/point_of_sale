<div class="row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            id="name"
            placeholder="Name" value="{{ old('name', $product->name ?? '') }}">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="category_id">Category</label>
        <select class="form-control" name="category_id" required>
            <option selected="" disabled>-- Select Category --</option>
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
                <select name="tax_rate_id" class="form-control" id="tax_rate" required>
                    <option selected="">-- Select Taxrate --</option>
                    @foreach ($taxRates as $rate)
                        <option value="{{ $rate->id }}" {{ old('tax_rate_id', $product->tax_rate_id ?? '') == $rate->id ? 'selected' : '' }}>{{ $rate->name }}</option>
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
                    placeholder="Selling Price" value="{{ old('price', $product->price ?? '') }}">
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
                        <input type="radio" id="taxIncludedYes" name="tax_included" class="custom-control-input" value="1" checked>
                        <label class="custom-control-label" for="taxIncludedYes">Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="taxIncludedNo" name="tax_included" class="custom-control-input" value="0">
                        <label class="custom-control-label" for="taxIncludedNo">No</label>
                    </div>
                </div>
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
                <input type="radio" id="stockableYes" name="stockable" class="custom-control-input" value="1"
                    {{ old('stockable', $product->stockable ?? '') == 1 ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="stockableYes">Yes</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="stockableNo" name="stockable" class="custom-control-input" value="0"
                    {{ old('stockable', $product->stockable ?? '') == 0 ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="stockableNo">No</label>
            </div>
        </div>
    </div>

    <div class="card col-md-12" id="stockInformation" style="display: none;">
        <div class="card-header">
            Stock Information
        </div>
        <div class="card-body row">
            <div class="form-group col-md-12">
                <label for="supplier_id">Supplier</label>
                <select class="form-control" name="supplier_id" required>
                    <option selected="">-- Select Supplier --</option>
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
                    placeholder="code" value="{{ old('name', $product->code ?? '') }}">
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
                <label for="store">Store</label>
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

        </div>
    </div>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('products.index') }}">Cancel</a>
