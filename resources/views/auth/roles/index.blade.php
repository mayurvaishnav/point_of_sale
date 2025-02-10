@extends('layouts.adminlte-app')

@section('title', 'Roles')

@section('custom_content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Role Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('role-create')
                    <a href="{{route('roles.create')}}" class="btn btn-primary btn-sm mb-2"><i class="fa fa-plus"></i> Add Role</a>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('custom_content')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @php
                                $groupedPermissions = $role->permissions->groupBy('group_name');
                            @endphp
                            @foreach ($groupedPermissions as $groupName => $permissions)
                                <b>{{ $groupName }}</b>
                                <ul class="list d-flex flex-wrap">
                                    @foreach ($permissions as $permission)
                                        <li class="mr-5">{{ $permission->name }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </td>
                        <td>
                            @can('role-edit')
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fas fa-edit"></i></a>
                            @endcan

                            @can('role-delete')
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('custom_js')
    <script>
        $(".btn-delete").click(function(e){
            e.preventDefault();
            var form = $(this).parents("form");

            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.value) {
                form.submit();
            }
            });

        });
    </script>
@stop
