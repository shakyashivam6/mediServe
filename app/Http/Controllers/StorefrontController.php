<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomerAddress;
use App\Models\Prescription;
use App\Models\ShopOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function removeFromCart(Request $request, Product $product): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $query = DB::table('cart_items')->where('product_id', $product->id);
        auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('guest_session_id', $this->guestId($request));
        $query->delete();
        if ($request->expectsJson()) {
            return response()->json($this->cartState($request, $product) + ['message' => 'Item removed from your cart.']);
        }
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
        if (! auth()->user()->hasCompleteProfile()) {
            $request->session()->put('checkout_after_otp', true);
            return redirect()->route('customer.profile.edit');
        }

        $user = $request->user();
        if (! $user->customerAddresses()->exists()) {
            $user->customerAddresses()->create([
                'label' => 'Profile address',
                'recipient_name' => trim($user->first_name.' '.$user->second_name),
                'mobile' => $user->mobile,
                'address_line' => $user->address_line,
                'pincode' => $user->pincode,
                'is_default' => true,
            ]);
        }
        $addresses = $user->customerAddresses()->get();

        return view('storefront.checkout', compact('addresses'));
    }

    public function selectCheckoutAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'address_id' => ['nullable', 'integer'],
            'label' => ['required_without:address_id', 'nullable', 'in:Home,Office,Other'],
            'recipient_name' => ['required_without:address_id', 'nullable', 'string', 'max:150'],
            'mobile' => ['required_without:address_id', 'nullable', 'digits:10'],
            'address_line' => ['required_without:address_id', 'nullable', 'string', 'max:1000'],
            'pincode' => ['required_without:address_id', 'nullable', 'digits:6'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (! empty($data['address_id'])) {
            $address = $request->user()->customerAddresses()->findOrFail($data['address_id']);
        } else {
            $address = $request->user()->customerAddresses()->create([
                ...$data,
                'is_default' => false,
            ]);
        }

        $request->session()->put('checkout_address_id', $address->id);

        return redirect()->route('checkout.review');
    }

    public function checkoutReview(Request $request): RedirectResponse|View
    {
        $address = $this->checkoutAddress($request);
        if (! $address) return redirect()->route('checkout')->withErrors(['address' => 'Choose a delivery address to continue.']);
        $items = $this->checkoutItems($request);
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('shop_status', 'Your cart is empty.');
        $subtotal = $items->sum(fn ($item) => (float) ($item->price ?? 0) * $item->quantity);

        return view('storefront.checkout-review', compact('items', 'subtotal', 'address'));
    }

    public function checkoutPayment(Request $request): RedirectResponse|View
    {
        $address = $this->checkoutAddress($request);
        if (! $address) return redirect()->route('checkout');
        $items = $this->checkoutItems($request);
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('shop_status', 'Your cart is empty.');
        $subtotal = $items->sum(fn ($item) => (float) ($item->price ?? 0) * $item->quantity);

        return view('storefront.checkout-payment', compact('items', 'subtotal', 'address'));
    }

    public function placeOrder(Request $request): RedirectResponse|View
    {
        $data = $request->validate(['payment_method' => ['required', 'in:cod,cashfree']]);
        $address = $this->checkoutAddress($request);
        if (! $address) return redirect()->route('checkout')->withErrors(['address' => 'Choose a delivery address to continue.']);

        if ($data['payment_method'] === 'cashfree') {
            return $this->startCashfreePayment($request, $address);
        }

        $order = $this->createShopOrder($request, $address, 'cod');
        DB::table('cart_items')->where('user_id', $request->user()->id)->delete();
        $request->session()->forget('checkout_address_id');

        return redirect()->route('checkout.complete', $order);
    }

    private function createShopOrder(Request $request, CustomerAddress $address, string $paymentMethod): ShopOrder
    {
        return DB::transaction(function () use ($request, $address, $paymentMethod) {
            $rows = DB::table('cart_items')->join('products', 'products.id', '=', 'cart_items.product_id')
                ->where('cart_items.user_id', $request->user()->id)->where('products.is_active', true)
                ->select('cart_items.product_id', 'cart_items.quantity', 'products.name', 'products.manufacturer', 'products.price')
                ->lockForUpdate()->get();
            abort_if($rows->isEmpty(), 422, 'Your cart is empty.');
            abort_if($rows->contains(fn ($row) => $row->price === null), 422, 'An item in your cart is awaiting a price and cannot be ordered yet.');

            $items = $rows->map(fn ($row) => [
                'product_id' => $row->product_id,
                'name' => $row->name,
                'manufacturer' => $row->manufacturer,
                'price' => (float) $row->price,
                'quantity' => (int) $row->quantity,
            ])->values()->all();
            $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
            abort_if($subtotal <= 0, 422, 'The order total must be greater than zero.');

            return ShopOrder::create([
                'order_number' => 'MS-'.now()->format('ymd').'-'.strtoupper(Str::random(6)),
                'user_id' => $request->user()->id,
                'customer_address_id' => $address->id,
                'items' => $items,
                'subtotal' => $subtotal,
                'delivery_address' => $address->recipient_name."\n".$address->mobile."\n".$address->address_line."\n".$address->pincode,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status' => $paymentMethod === 'cashfree' ? 'payment_pending' : 'placed',
            ]);
        });
    }

    private function startCashfreePayment(Request $request, CustomerAddress $address): RedirectResponse|View
    {
        $appId = config('services.cashfree.app_id');
        $secret = config('services.cashfree.secret_key');
        if (! $appId || ! $secret) {
            return back()->withErrors(['payment' => 'Cashfree sandbox credentials are missing. Set CASHFREE_APP_ID and CASHFREE_SECRET_KEY in .env.']);
        }

        $order = $this->createShopOrder($request, $address, 'cashfree');
        $order->update(['cashfree_order_id' => $order->order_number]);
        $baseUrl = rtrim((string) config('services.cashfree.base_url'), '/');
        $returnUrl = route('checkout.cashfree.return', absolute: true).'?order_id={order_id}';

        try {
            $response = Http::acceptJson()->asJson()->timeout(25)->withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secret,
                'x-api-version' => config('services.cashfree.api_version', '2025-01-01'),
            ])->post($baseUrl.'/orders', [
                'order_id' => $order->cashfree_order_id,
                'order_amount' => (float) $order->subtotal,
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id' => (string) $request->user()->id,
                    'customer_name' => $address->recipient_name,
                    'customer_email' => $request->user()->email ?: 'customer'.$request->user()->id.'@mediserve.local',
                    'customer_phone' => $address->mobile,
                ],
                'order_meta' => ['return_url' => $returnUrl],
                'order_note' => 'MediServe order '.$order->order_number,
            ]);

            if (! $response->successful() || ! $response->json('payment_session_id')) {
                Log::warning('Cashfree sandbox order creation failed.', ['order' => $order->order_number, 'http_status' => $response->status()]);
                $order->update(['payment_status' => 'failed', 'status' => 'payment_failed']);
                return back()->withErrors(['payment' => 'Cashfree could not start the payment. Check sandbox credentials and try again.']);
            }

            $order->update(['cashfree_payment_session_id' => $response->json('payment_session_id')]);

            return view('storefront.cashfree-redirect', [
                'paymentSessionId' => $order->cashfree_payment_session_id,
                'mode' => config('services.cashfree.mode', 'sandbox'),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Cashfree sandbox request failed.', ['order' => $order->order_number, 'exception' => $exception::class]);
            $order->update(['payment_status' => 'failed', 'status' => 'payment_failed']);

            return back()->withErrors(['payment' => 'Cashfree is not reachable right now. Please try again.']);
        }
    }

    public function cashfreeReturn(Request $request): RedirectResponse
    {
        $cashfreeOrderId = $request->query('order_id');
        abort_unless(is_string($cashfreeOrderId) && $cashfreeOrderId !== '', 404);
        $order = ShopOrder::where('user_id', $request->user()->id)
            ->where('cashfree_order_id', $cashfreeOrderId)->firstOrFail();
        $appId = config('services.cashfree.app_id');
        $secret = config('services.cashfree.secret_key');
        abort_unless($appId && $secret, 503, 'Cashfree credentials are not configured.');

        try {
            $baseUrl = rtrim((string) config('services.cashfree.base_url'), '/');
            $response = Http::acceptJson()->timeout(20)->withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secret,
                'x-api-version' => config('services.cashfree.api_version', '2025-01-01'),
            ])->get($baseUrl.'/orders/'.rawurlencode($cashfreeOrderId).'/payments');

            if ($response->successful()) {
                $payments = collect($response->json());
                $paidPayment = $payments->first(fn ($payment) =>
                    ($payment['payment_status'] ?? null) === 'SUCCESS'
                    && abs((float) ($payment['payment_amount'] ?? 0) - (float) $order->subtotal) < 0.01
                    && ($payment['payment_currency'] ?? 'INR') === 'INR'
                );

                if ($paidPayment) {
                    DB::transaction(function () use ($request, $order, $paidPayment) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'placed',
                        ]);
                        DB::table('cart_items')->where('user_id', $request->user()->id)->delete();
                    });

                    $request->session()->forget('checkout_address_id');

                    return redirect()->route('checkout.complete', $order);
                }

                $failedPayment = $payments->contains(fn ($payment) => in_array(
                    $payment['payment_status'] ?? null,
                    ['FAILED', 'CANCELLED', 'USER_DROPPED'],
                    true
                ));
                if ($failedPayment) {
                    $order->update(['payment_status' => 'failed', 'status' => 'payment_failed']);
                }
            }
        } catch (\Throwable $exception) {
            Log::warning('Cashfree payment verification failed.', ['order' => $order->order_number, 'exception' => $exception::class]);
        }

        if ($order->customer_address_id) {
            $request->session()->put('checkout_address_id', $order->customer_address_id);
        }

        return redirect()->route('checkout.payment')->withErrors(['payment' => 'Payment was not confirmed. Your cart is still saved; you can try again.']);
    }

    private function checkoutAddress(Request $request): ?CustomerAddress
    {
        $id = $request->session()->get('checkout_address_id');

        return $id ? $request->user()->customerAddresses()->find($id) : null;
    }

    private function checkoutItems(Request $request)
    {
        return DB::table('cart_items')->join('products', 'products.id', '=', 'cart_items.product_id')
            ->where('cart_items.user_id', $request->user()->id)->where('products.is_active', true)
            ->select('cart_items.quantity', 'products.id', 'products.name', 'products.manufacturer', 'products.price', 'products.images')
            ->get();
    }

    public function orderComplete(Request $request, ShopOrder $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('storefront.order-complete', compact('order'));
    }

    public function orders(Request $request): View
    {
        $selectedPrescription = null;
        if ($request->filled('prescription')) {
            $selectedPrescription = Prescription::query()
                ->where('user_id', $request->user()->id)
                ->whereIn('status', ['confirmed', 'dispatched', 'delivered'])
                ->findOrFail($request->integer('prescription'));
        }

        $shopOrders = ($selectedPrescription ? collect() : ShopOrder::query()
            ->where('user_id', $request->user()->id)
            ->get())
            ->map(function (ShopOrder $order) {
                $order->setAttribute('history_type', 'shop');
                $order->setAttribute('history_total', $order->subtotal);
                $order->setAttribute('history_discount', 0);
                $order->setAttribute('history_status_label', match ($order->status) {
                    'payment_pending' => 'Payment pending',
                    'payment_failed' => 'Payment failed',
                    default => ucfirst(str_replace('_', ' ', $order->status)),
                });
                $order->setAttribute('history_payment_label', $order->payment_method === 'cashfree'
                    ? 'Cashfree · '.ucfirst($order->payment_status)
                    : 'Cash on delivery');
                $order->setAttribute('history_url', $order->status === 'placed'
                    ? route('checkout.complete', $order)
                    : null);
                return $order;
            });

        $prescriptionOrders = Prescription::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['confirmed', 'dispatched', 'delivered'])
            ->when($selectedPrescription, fn ($query) => $query->whereKey($selectedPrescription->id))
            ->get()
            ->map(function (Prescription $prescription) {
                $prescription->setAttribute('history_type', 'prescription');
                $prescription->setAttribute('history_total', $prescription->total_amount);
                $prescription->setAttribute('history_discount', $prescription->discount_amount);
                $prescription->setAttribute('history_status_label', $prescription->customerStatusLabel());
                $prescription->setAttribute('history_payment_label', $prescription->payment_method
                    ? ($prescription->payment_method === 'cod' ? 'Cash on delivery' : 'Prepaid')
                    : 'Payment method pending');
                $prescription->setAttribute('history_url', route('customer.prescriptions.show', $prescription));
                return $prescription;
            });

        $allOrders = $shopOrders->concat($prescriptionOrders)
            ->sortByDesc('created_at')
            ->values();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $orders = new LengthAwarePaginator(
            $allOrders->forPage($page, 10)->values(),
            $allOrders->count(),
            10,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()],
        );

        return view('storefront.orders', compact('orders'));
    }

    public function customerAddresses(Request $request): View
    {
        $addresses = $request->user()->customerAddresses()->get();

        return view('Customer.profile.addresses', compact('addresses'));
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
