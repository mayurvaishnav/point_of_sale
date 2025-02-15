@extends('layouts.adminlte-app')

@section('title', 'Customers')
@section('custom_content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customers</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('customer-create')
                    <a href="{{route('customers.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer</a>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
@endsection

@section('custom_content')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Registration No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr>
                    <td>{{$customer->id}}</td>
                    <td>{{$customer->name}}</td>
                    <td>{{$customer->phone}}</td>
                    <td>{{$customer->registration_no}}</td>
                    <td>
                        @can('customer-edit')
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('customer-delete')
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-delete" data-url="{{route('customers.destroy', $customer)}}">
                                    <i class="fas fa-trash"></i>
                                </button>
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

