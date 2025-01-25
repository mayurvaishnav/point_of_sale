<div class="form-group">
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

<div class="form-group">
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

<div class="form-group">
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

<div class="form-group">
    <label for="tax_rate">Tax rate</label>
    <input type="number" name="tax_rate" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate"
           placeholder="tax_rate" value="{{ old('tax_rate', $product->tax_rate ?? '') }}">
    @error('tax_rate')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
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

<div class="form-group">
    <label for="selling_price">Puying Price</label>
    <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
           id="selling_price"
           placeholder="Selling Price" value="{{ old('selling_price', $product->selling_price ?? '') }}">
    @error('selling_price')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="tax">Tax</label>
    <input type="number" step="0.01" name="tax" class="form-control @error('tax') is-invalid @enderror"
           id="tax"
           placeholder="tax" value="{{ old('tax', $product->tax ?? '') }}">
    @error('tax')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
           placeholder="quantity" value="{{ old('quantity', $product->quantity ?? '') }}">
    @error('quantity')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
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


<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('products.index') }}">Cancel</a>