<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'manufacturer' => ['nullable', 'string', 'max:191'],
            'packaging' => ['nullable', 'string', 'max:191'],
            'rx' => ['nullable', 'in:rx,otc'],
            'deal' => ['nullable', 'in:discounted'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:price_low,price_high,name_desc'],
        ]);

        $guestId = $this->guestId($request);
        $userId = auth()->id();
        $savedIds = DB::table('wishlist_items')->where('user_id', $userId)
            ->when(! $userId, fn ($q) => $q->where('guest_session_id', $guestId))
            ->pluck('product_id')->all();
        $cartCount = DB::table('cart_items')->where('user_id', $userId)
            ->when(! $userId, fn ($q) => $q->where('guest_session_id', $guestId))
            ->sum('quantity');
        $cartQuantities = $this->cartQuery($request)->pluck('quantity', 'product_id')->all();

        $query = Product::query()->where('is_active', true)
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$request->q.'%')->orWhere('manufacturer', 'like', '%'.$request->q.'%')->orWhere('composition', 'like', '%'.$request->q.'%')))
            ->when($request->filled('manufacturer'), fn ($q) => $q->where('manufacturer', $request->input('manufacturer')))
            ->when($request->filled('packaging'), fn ($q) => $q->where('packaging', 'like', '%'.$request->input('packaging').'%'))
            ->when($request->input('rx') === 'rx', fn ($q) => $q->where('requires_prescription', true))
            ->when($request->input('rx') === 'otc', fn ($q) => $q->where('requires_prescription', false))
            ->when($request->input('deal') === 'discounted', fn ($q) => $q->whereNotNull('price')->whereNotNull('mrp')->whereColumn('mrp', '>', 'price'))
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', (float) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', (float) $request->input('max_price')));

        match ($request->input('sort')) {
            'price_low' => $query->orderByRaw('price IS NULL')->orderBy('price')->orderBy('name'),
            'price_high' => $query->orderByRaw('price IS NULL')->orderByDesc('price')->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->orderBy('name'),
        };

        $products = $query->paginate(24)->withQueryString();
        $manufacturers = Product::query()->where('is_active', true)->whereNotNull('manufacturer')
            ->where('manufacturer', '<>', '')->distinct()->orderBy('manufacturer')->pluck('manufacturer');

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('storefront.partials.products', compact('products', 'savedIds', 'cartQuantities'))->render(),
                'total' => $products->total(),
            ]);
        }

        return view('storefront.index', compact('products', 'savedIds', 'cartCount', 'cartQuantities', 'manufacturers'));
    }

    public function suggestions(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        if (mb_strlen($query) < 3) {
            return response()->json(['data' => []]);
        }

        $products = Product::query()->where('is_active', true)
            ->where(fn ($builder) => $builder
                ->where('name', 'like', '%'.$query.'%')
                ->orWhere('manufacturer', 'like', '%'.$query.'%')
                ->orWhere('composition', 'like', '%'.$query.'%'))
            ->orderBy('name')->limit(8)->get(['id', 'name', 'manufacturer', 'composition', 'price']);

        return response()->json(['data' => $products->map(fn (Product $product) => [
            'url' => route('products.show', $product),
            'name' => $product->name,
            'manufacturer' => $product->manufacturer,
            'composition' => Str::limit((string) $product->composition, 72),
            'price' => $product->price !== null ? '₹'.number_format((float) $product->price, 2) : 'Price on request',
        ])]);
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $guestId = $this->guestId($request);
        $userId = auth()->id();
        $saved = DB::table('wishlist_items')->where('product_id', $product->id)
            ->when($userId, fn ($query) => $query->where('user_id', $userId), fn ($query) => $query->where('guest_session_id', $guestId))
            ->exists();
        $quantity = (int) $this->cartQuery($request)->where('product_id', $product->id)->value('quantity');
        $cartCount = (int) $this->cartQuery($request)->sum('quantity');

        return view('storefront.show', compact('product', 'saved', 'quantity', 'cartCount'));
    }

    public function beginPrescriptionUpload(Request $request): RedirectResponse
    {
        if (! auth()->check() || auth()->user()->role !== 'customer') {
            $request->session()->put('upload_prescription_after_otp', true);
            return redirect()->route('customer.login')->with('status', 'Verify your mobile number to upload a prescription.');
        }

        if (! auth()->user()->hasCompleteProfile()) {
            $request->session()->put('upload_prescription_after_otp', true);
            return redirect()->route('customer.profile.edit')->with('status', 'Complete your details before uploading a prescription.');
        }

        return redirect()->route('customer.prescriptions.create');
    }

    public function addToCart(Request $request, Product $product): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless($product->is_active, 404);
        $this->upsertItem('cart_items', $request, $product, (int) $request->input('quantity', 1));
        if ($request->expectsJson()) {
            return response()->json($this->cartState($request, $product) + ['message' => 'Added to your cart.']);
        }
        return back()->with('shop_status', 'Added to your cart.');
    }

    public function updateCartQuantity(Request $request, Product $product): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $query = $this->cartQuery($request)->where('product_id', $product->id);
        abort_unless($query->exists(), 404);
        $query->update(['quantity' => $data['quantity'], 'updated_at' => now()]);
        if ($request->expectsJson()) {
            return response()->json($this->cartState($request, $product) + ['message' => 'Cart updated.']);
        }
        return back()->with('shop_status', 'Cart updated.');
    }

    public function toggleWishlist(Request $request, Product $product): RedirectResponse
    {
        $guestId = auth()->check() ? null : $this->guestId($request);
        $query = DB::table('wishlist_items')->where('product_id', $product->id);
        $query = auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('guest_session_id', $guestId);
        if ($query->exists()) {
            $query->delete();
            return back()->with('shop_status', 'Removed from your wishlist.');
        }
        DB::table('wishlist_items')->insert(['guest_session_id' => $guestId, 'user_id' => auth()->id(), 'product_id' => $product->id, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('shop_status', 'Saved to your wishlist.');
    }

    public function cart(Request $request): View
    {
        $guestId = auth()->check() ? null : $this->guestId($request);
        $items = DB::table('cart_items')->join('products', 'products.id', '=', 'cart_items.product_id')
            ->where('products.is_active', true)
            ->when(auth()->check(), fn ($q) => $q->where('cart_items.user_id', auth()->id()), fn ($q) => $q->where('cart_items.guest_session_id', $guestId))
            ->select('cart_items.quantity', 'products.*')->get();
        return view('storefront.cart', compact('items'));
    }

    public function wishlist(Request $request): View
    {
        $guestId = auth()->check() ? null : $this->guestId($request);
        $items = DB::table('wishlist_items')->join('products', 'products.id', '=', 'wishlist_items.product_id')
            ->where('products.is_active', true)
            ->when(auth()->check(), fn ($q) => $q->where('wishlist_items.user_id', auth()->id()), fn ($q) => $q->where('wishlist_items.guest_session_id', $guestId))
            ->select('products.*')->get();
        return view('storefront.wishlist', compact('items'));
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $query = DB::table('cart_items')->where('product_id', $product->id);
        auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('guest_session_id', $this->guestId($request));
        $query->delete();
        return back()->with('shop_status', 'Item removed from your cart.');
    }

    public function checkout(Request $request): RedirectResponse|View
    {
        $count = $this->cartQuery($request)->count();
        if (! $count) return redirect()->route('home')->with('shop_status', 'Your cart is empty.');
        if (! auth()->check()) {
            $request->session()->put('checkout_after_otp', true);
            return redirect()->route('customer.login')->with('status', 'Enter your mobile number to verify and continue checkout.');
        }
        return view('storefront.checkout');
    }

    private function guestId(Request $request): string
    {
        $id = $request->session()->get('guest_shopper_id');
        if (! $id || ! DB::table('guest_sessions')->where('id', $id)->exists()) {
            $id = (string) Str::uuid();
            DB::table('guest_sessions')->insert(['id' => $id, 'created_at' => now(), 'updated_at' => now()]);
            $request->session()->put('guest_shopper_id', $id);
        }
        return $id;
    }

    private function upsertItem(string $table, Request $request, Product $product, int $quantity): void
    {
        $quantity = max(1, min(99, $quantity));
        $guestId = auth()->check() ? null : $this->guestId($request);
        $query = DB::table($table)->where('product_id', $product->id);
        auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('guest_session_id', $guestId);
        $row = $query->first();
        if ($row) {
            $data = ['updated_at' => now()];
            if ($table === 'cart_items') $data['quantity'] = min(99, $row->quantity + $quantity);
            DB::table($table)->where('id', $row->id)->update($data);
        } else {
            $data = ['guest_session_id' => $guestId, 'user_id' => auth()->id(), 'product_id' => $product->id, 'created_at' => now(), 'updated_at' => now()];
            if ($table === 'cart_items') $data['quantity'] = $quantity;
            DB::table($table)->insert($data);
        }
    }

    private function cartQuery(Request $request)
    {
        $query = DB::table('cart_items');
        return auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('guest_session_id', $this->guestId($request));
    }

    private function cartState(Request $request, Product $product): array
    {
        $quantity = (int) $this->cartQuery($request)->where('product_id', $product->id)->value('quantity');
        $items = DB::table('cart_items')->join('products', 'products.id', '=', 'cart_items.product_id');
        $items = auth()->check() ? $items->where('cart_items.user_id', auth()->id()) : $items->where('cart_items.guest_session_id', $this->guestId($request));
        return [
            'productId' => $product->id,
            'quantity' => $quantity,
            'cartCount' => (int) $this->cartQuery($request)->sum('quantity'),
            'lineTotal' => $product->price !== null ? number_format((float) $product->price * $quantity, 2, '.', '') : null,
            'subtotal' => number_format((float) ($items->selectRaw('SUM(products.price * cart_items.quantity) as subtotal')->first()?->subtotal ?? 0), 2, '.', ''),
        ];
    }
}
