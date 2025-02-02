@extends('adminlte::page')

@section('title', 'Suppliers')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Suppliers</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('supplier-create')
                    <a href="{{route('suppliers.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Supplier</a>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('content')

@include('layouts.alerts')

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="suppliers-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{$supplier->id}}</td>
                    <td>{{$supplier->name}}</td>
                    <td>{{$supplier->email}}</td>
                    <td>{{$supplier->phone}}</td>
                    <td>{{$supplier->address}}</td>
                    <td>
                        @can('supplier-edit')
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        @endcan
                        
                        @can('supplier-delete')
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-delete" data-url="{{route('suppliers.destroy', $supplier)}}">
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

@section('js')
    <script>
        $(document).ready(function() {
            $('#suppliers-table').DataTable({
                "pageLength": 50,
            });
        });

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

