@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('user-create')
                    <a href="{{route('users.create')}}" class="btn btn-primary btn-sm mb-2"><i class="fa fa-plus"></i> Add User</a>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('content')

@include('layouts.alerts')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id}}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $role)
                            <label class="badge bg-info">{{ $role }}</label>
                            @endforeach
                        @endif
                        </td>
                        <td>
                            @can('user-edit')
                                <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fas fa-edit"></i></a>
                            @endcan
        
                            @can('user-delete')
                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
            
                                    <button type="submit" class="btn btn-danger btn-sm  btn-delete"><i class="fas fa-trash"></i></button>
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

@section('js')
    <script type="text/javascript">
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
