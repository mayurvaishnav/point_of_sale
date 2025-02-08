<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="Name" value="{{ old('name', $role->name ?? '') }}">
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="permission">Permissions:</label>
    @error('permission')
    <span class="invalid-feedback" role="alert" style="display: block;">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <div>
        @foreach($groupedPermissions as $groupName => $permissions)
            <p><strong>{{ $groupName }}</strong><br/>
            @foreach($permissions as $permission)
                <label class="form-check-label">
                    <input type="checkbox" name="permission[]" 
                        value="{{$permission->id}}" 
                        {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : ''}}
                        class="{{ $errors->has('permission') ? 'is-invalid' : '' }}"
                    >
                    {{ $permission->name }}
                </label>
                <br/>
            @endforeach
            </p>
        @endforeach
    </div>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('roles.index') }}">Cancel</a>