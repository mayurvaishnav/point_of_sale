<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="Name" value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="slug">Slug</label>
    <input type="text" name="slug" readonly class="form-control @error('slug') is-invalid @enderror"
           id="slug"
           placeholder="slug" value="{{ old('slug', $category->slug ?? '') }}">
    @error('slug')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="sorting_order">Sorting Order</label>
    <input type="number" name="sorting_order" class="form-control @error('sorting_order') is-invalid @enderror"
           id="sorting_order"
           placeholder="Sorting Order" value="{{ old('sorting_order', $category->sorting_order ?? '') }}" required>
    @error('sorting_order')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
           id="description"
           placeholder="Description">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('categories.index') }}">Cancel</a>

<script>
    // Slug Generator
    const title = document.querySelector("#name");
    const slug = document.querySelector("#slug");
    title.addEventListener("keyup", function() {
        let preslug = title.value;
        preslug = preslug.replace(/[^a-zA-Z0-9-_ ]/g, '');
        preslug = preslug.replace(/ /g,"-");
        slug.value = preslug.toLowerCase();
    });
</script>