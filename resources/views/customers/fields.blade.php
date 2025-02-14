<div class="row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            id="name"
            placeholder="Name" value="{{ old('name', $customer->name ?? '') }}" required>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
            placeholder="Email" value="{{ old('email', $customer->email ?? '') }}">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="phone">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone"
            placeholder="Phone" value="{{ old('phone', $customer->phone ?? '') }}">
        @error('phone')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="company">Company</label>
        <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
            id="company"
            placeholder="Company" value="{{ old('company', $customer->company ?? '') }}">
        @error('company')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-12">
        <label for="address">Address</label>
        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
            id="address"
            placeholder="Address" value="{{ old('address', $customer->address ?? '') }}">
        @error('address')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>



    <div class="form-group col-md-6">
        <label for="brand">Car brand</label>
        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
            id="brand"
            placeholder="brand" value="{{ old('brand', $customer->brand ?? '') }}">
        @error('brand')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>



    <div class="form-group col-md-6">
        <label for="model">Car model</label>
        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
            id="model"
            placeholder="Model" value="{{ old('model', $customer->model ?? '') }}">
        @error('model')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>



    <div class="form-group col-md-6">
        <label for="registration_no">Registration no</label>
        <input type="text" name="registration_no" class="form-control @error('registration_no') is-invalid @enderror"
            id="registration_no"
            placeholder="Registration no" value="{{ old('registration_no', $customer->registration_no ?? '') }}">
        @error('registration_no')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-12">
        <label for="description">Description</label>
        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
            id="description"
            placeholder="Description">{{ old('description', $customer->description ?? '') }}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('customers.index') }}">Cancel</a>
