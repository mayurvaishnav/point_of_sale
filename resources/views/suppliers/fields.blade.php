<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="Name" value="{{ old('name', $supplier->name ?? '') }}" required>
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
           placeholder="Email" value="{{ old('email', $supplier->email ?? '') }}">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone"
           placeholder="Phone" value="{{ old('phone', $supplier->phone ?? '') }}">
    @error('phone')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="address">Address</label>
    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
           id="address"
           placeholder="Address" value="{{ old('address', $supplier->address ?? '') }}">
    @error('address')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="company">Company</label>
    <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
           id="company"
           placeholder="Company" value="{{ old('company', $supplier->company ?? '') }}">
    @error('company')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
           id="description"
           placeholder="Description">{{ old('description', $supplier->description ?? '') }}</textarea>
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('suppliers.index') }}">Cancel</a>