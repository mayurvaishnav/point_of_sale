<div class="form-group">
    <label for="name">{{ __('customer.Name') }}</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="{{ __('customer.Name') }}" value="{{ old('name', $customer->name ?? '') }}">
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="email">{{ __('customer.Email') }}</label>
    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
           placeholder="{{ __('customer.Email') }}" value="{{ old('email', $customer->email ?? '') }}">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="phone">{{ __('customer.Phone') }}</label>
    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone"
           placeholder="{{ __('customer.Phone') }}" value="{{ old('phone', $customer->phone ?? '') }}">
    @error('phone')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="address">{{ __('customer.Address') }}</label>
    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
           id="address"
           placeholder="{{ __('customer.Address') }}" value="{{ old('address', $customer->address ?? '') }}">
    @error('address')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="company">{{ __('customer.Company') }}</label>
    <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
           id="company"
           placeholder="{{ __('customer.Company') }}" value="{{ old('company', $customer->company ?? '') }}">
    @error('company')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="description">{{ __('customer.Description') }}</label>
    <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
           id="description"
           placeholder="{{ __('customer.Description') }}">{{ old('description', $customer->description ?? '') }}</textarea>
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


<button class="btn btn-primary" type="submit">{{ __('common.Save') }}</button>
<a class="btn btn-default" href="{{ route('customers.index') }}">Cancel</a>