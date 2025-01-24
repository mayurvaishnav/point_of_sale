@if (Session::has('success'))
<div class="alert alert-primary">
    {{Session::get('success')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="ri-close-line"></i>
    </button>
</div>
@endif