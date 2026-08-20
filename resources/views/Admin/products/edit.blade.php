<x-layouts.admin-layout title="Edit Product">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="header-title mb-0">Edit Product</h4>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-soft-secondary btn-sm">
                            <i class="ri-arrow-left-line align-middle"></i> Back to list
                        </a>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" value="{{ $product->code ?? '—' }}" disabled>
                            <div class="form-text">Auto-generated fast-search code — never changes.</div>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Item ID</label>
                            <input type="text" class="form-control" value="{{ $product->item_id }}" disabled>
                            <div class="form-text">The import sheet's own ID — re-uploading the sheet matches this row against it.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.products.update', $product) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="composition" class="form-label">Composition</label>
                            <textarea name="composition" id="composition" rows="2" class="form-control @error('composition') is-invalid @enderror">{{ old('composition', $product->composition) }}</textarea>
                            @error('composition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="manufacturer" class="form-label">Manufacturer</label>
                                <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer', $product->manufacturer) }}">
                                @error('manufacturer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="packaging" class="form-label">Packaging</label>
                                <input type="text" name="packaging" id="packaging" class="form-control @error('packaging') is-invalid @enderror" value="{{ old('packaging', $product->packaging) }}">
                                @error('packaging')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mrp" class="form-label">MRP (₹)</label>
                                <input type="number" step="0.01" min="0" name="mrp" id="mrp" class="form-control @error('mrp') is-invalid @enderror" value="{{ old('mrp', $product->mrp) }}">
                                @error('mrp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Selling Price (₹)</label>
                                <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="uses" class="form-label">Use of Medicine</label>
                            <textarea name="uses" id="uses" rows="2" class="form-control @error('uses') is-invalid @enderror">{{ old('uses', $product->uses) }}</textarea>
                            @error('uses')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-4 mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="requires_prescription" value="1" class="form-check-input" id="requires_prescription" {{ old('requires_prescription', $product->requires_prescription) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_prescription">Requires prescription (Rx)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
