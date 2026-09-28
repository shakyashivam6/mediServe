<x-layouts.admin-layout title="Carousel">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="header-title mb-1">Carousel</h4>
                            <p class="text-muted font-14 mb-0">Slides shown at the top of the storefront homepage. Hidden from the homepage automatically when there are no active slides.</p>
                        </div>
                        <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i> Add Slide
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Image</th>
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th style="width: 90px;">Order</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carousels as $carousel)
                                    <tr>
                                        <td><img src="{{ $carousel->image_url }}" alt="" style="width:96px;height:54px;object-fit:cover;border-radius:6px;"></td>
                                        <td>{{ $carousel->title ?: '—' }}</td>
                                        <td>{{ $carousel->link ?: '—' }}</td>
                                        <td>{{ $carousel->sort_order }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.carousels.toggle-active', $carousel) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-soft-{{ $carousel->is_active ? 'success' : 'secondary' }} btn-sm">
                                                    {{ $carousel->is_active ? 'Active' : 'Hidden' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.carousels.edit', $carousel) }}" class="btn btn-soft-primary btn-sm me-1"><i class="ri-pencil-line"></i></a>
                                            <form method="POST" action="{{ route('admin.carousels.destroy', $carousel) }}" class="d-inline" onsubmit="return confirm('Remove this slide?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No carousel slides yet. Add one to show it on the homepage.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
