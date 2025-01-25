@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon fas fa-check"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="icon fas fa-exclamation-triangle"></i>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<script type="text/javascript">
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
</script>