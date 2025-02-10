@extends('layouts.adminlte-app')

@section('title', 'Schedule Jobs')
@section('custom_content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Schedule jobs</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- @can('job-create') --}}
                    <a href="{{route('jobs.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Job</a>
                {{-- @endcan --}}
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
                <th>Name</th>
                <th>Email Subject</th>
                <th>Frequency</th>
                <th>Execution Time</th>
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->job_name }}</td>
                    <td>{{ $job->email_subject }}</td>
                    <td>{{ $job->frequency }}</td>
                    <td>{{ $job->execution_time }}</td>
                    <td>{{ $job->is_active ? 'Active' : 'Paused' }}</td>
                    <td>
                        {{-- @can('job-edit') --}}
                            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        {{-- @endcan --}}
                        
                        {{-- @can('job-delete') --}}
                            <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-delete" data-url="{{route('jobs.destroy', $job)}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        {{-- @endcan --}}
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
