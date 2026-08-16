<x-layouts.admin-layout title="Import Products">

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Import Products from CSV/Excel</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="text-muted font-13">
                        Upload a <code>.xlsx</code>, <code>.xls</code> or <code>.csv</code> file with these columns
                        (case/spacing-insensitive header names): <code>item_id</code>, <code>drug name</code>,
                        <code>composition</code>, <code>manufacturer</code>, <code>mrp</code>, <code>price</code>,
                        <code>packaging</code>, <code>use of medicine</code>, and up to ten image columns
                        <code>image0</code>…<code>image9</code>.
                    </p>
                    <p class="text-muted font-13">
                        Rows are matched on <code>item_id</code> — re-uploading the same file (or an updated version of
                        it) updates existing products instead of creating duplicates. <code>requires_prescription</code>
                        and active/inactive aren't in the sheet; set those from the Products list after importing.
                    </p>

                    <form method="POST" action="{{ route('admin.products.import.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv"
                                class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Upload &amp; Import</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
