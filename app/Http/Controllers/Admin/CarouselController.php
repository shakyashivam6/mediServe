<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Admin > Content > Carousel. Slides shown at the top of the storefront
 * homepage (see StorefrontController::index / storefront.index). A store
 * with zero active slides means the homepage hides the carousel section
 * entirely rather than showing an empty one.
 */
class CarouselController extends Controller
{
    public function index()
    {
        return view('Admin.carousels.index', [
            'carousels' => Carousel::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('Admin.carousels.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        Carousel::create([
            'image' => $request->file('image')->store('carousels', 'public'),
            'title' => $data['title'] ?? null,
            'link' => $data['link'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.carousels.index')->with('status', 'Carousel slide added.');
    }

    public function edit(Carousel $carousel)
    {
        return view('Admin.carousels.edit', ['carousel' => $carousel]);
    }

    public function update(Request $request, Carousel $carousel)
    {
        $data = $this->validateRequest($request, isUpdate: true);

        $carousel->update([
            'image' => $request->hasFile('image')
                ? tap($request->file('image')->store('carousels', 'public'), fn () => Storage::disk('public')->delete($carousel->image))
                : $carousel->image,
            'title' => $data['title'] ?? null,
            'link' => $data['link'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.carousels.index')->with('status', 'Carousel slide updated.');
    }

    public function destroy(Carousel $carousel)
    {
        Storage::disk('public')->delete($carousel->image);
        $carousel->delete();

        return redirect()->route('admin.carousels.index')->with('status', 'Carousel slide removed.');
    }

    /**
     * Quick show/hide toggle from the list, without a full edit round-trip.
     */
    public function toggleActive(Carousel $carousel)
    {
        $carousel->update(['is_active' => ! $carousel->is_active]);

        return back()->with('status', 'Carousel slide '.($carousel->is_active ? 'shown' : 'hidden').' on the storefront.');
    }

    protected function validateRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', 'max:4096'],
            'title' => ['nullable', 'string', 'max:191'],
            'link' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
