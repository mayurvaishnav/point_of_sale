<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="Name" value="{{ old('name', $user->name ?? '') }}">
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="username">Username:</label>
    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
           id="username"
           placeholder="Username" value="{{ old('name', $user->username ?? '') }}">
    @error('username')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
           placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password"
           placeholder="Password">
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="confirm-password">Comfirm Password</label>
    <input type="password" name="confirm-password" class="form-control @error('confirm-password') is-invalid @enderror" 
            id="confirm-password"
           placeholder="Confirm Password">
    @error('confirm-password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="roles">Roles:</label>
    @error('roles')
    <span class="invalid-feedback" role="alert" style="display: block;">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <div>
        @foreach($roles as $role)
            <input type="checkbox" name="roles[]" 
                value="{{$role}}" 
                {{ in_array($role, $userRole ?? []) ? 'checked' : ''}}
                class="{{ $errors->has('role') ? 'is-invalid' : '' }}"
            >
            {{ $role }}
            <br/>
        @endforeach
    </div>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('users.index') }}">Cancel</a>