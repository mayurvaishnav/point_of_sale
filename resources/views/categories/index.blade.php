@extends('layouts.adminlte-app')

@section('title', 'Category')
@section('custom_content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('category-create')
                    <a href="{{route('categories.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Category</a>
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
                    <th>Slug</th>
                    <th>Sorting Order</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>{{$category->slug}}</td>
                    <td>{{$category->sorting_order}}</td>
                    <td>{{$category->description}}</td>
                    <td>
                        @can('category-edit')
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        @endcan

                        @can('category-delete')
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-delete" data-url="{{route('categories.destroy', $category)}}">
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

