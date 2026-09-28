<x-layouts.admin-layout title="Add Carousel Slide">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Carousel Slide</h4>

                    <form method="POST" action="{{ route('admin.carousels.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" required>
                            <div class="form-text">Recommended: a wide banner image (e.g. 1600×500).</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-muted">(optional)</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link <span class="text-muted">(optional — where the slide takes a visitor when clicked)</span></label>
                            <input type="text" name="link" id="link" value="{{ old('link') }}" class="form-control @error('link') is-invalid @enderror" placeholder="https://...">
                            @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-control @error('sort_order') is-invalid @enderror">
                            <div class="form-text">Lower numbers show first.</div>
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Show on homepage</label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Add Slide</button>
                        <a href="{{ route('admin.carousels.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
