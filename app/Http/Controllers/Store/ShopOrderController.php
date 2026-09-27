<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ShopOrder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShopOrderController extends Controller
{
    public function index()
    {
        // MediServe is currently configured for one store, so all storefront
        // orders belong to the signed-in Store.
        $orders = ShopOrder::query()->with(['customer', 'captain'])->latest()->paginate(25);

        return view('Store.orders.index', compact('orders'));
    }

    public function show(Request $request, ShopOrder $order)
    {
        $store = $this->currentStore($request);
        $canProcessOrders = $store !== null;
        $canAssignCaptains = $canProcessOrders;
        $captains = $canAssignCaptains
            ? $request->user()->captains()->where('isActive', true)->orderBy('first_name')->get()
            : collect();
        $order->load(['customer', 'captain']);

        return view('Store.orders.show', compact('order', 'captains', 'canProcessOrders', 'canAssignCaptains'));
    }

    public function assignCaptain(Request $request, ShopOrder $order)
    {
        abort_unless($order->fulfillment_status === 'accepted', 422, 'Only accepted orders can be assigned.');
        $store = $this->currentStore($request);
        abort_unless($store, 403, 'An approved store is required to assign a captain.');

        $data = $request->validate(['captain_id' => ['required', 'integer', 'exists:users,id']]);
        $captain = $request->user()->captains()->where('isActive', true)->find($data['captain_id']);
        if (! $captain) {
            throw ValidationException::withMessages(['captain_id' => 'Choose an active captain assigned to your store.']);
        }

        $order->update(['captain_id' => $captain->id]);

        return redirect()->route('store.orders.show', $order)->with('status', "Captain assigned to order {$order->order_number}.");
    }

    public function accept(Request $request, ShopOrder $order)
    {
        abort_unless($this->currentStore($request), 403, 'Your store must be approved to process orders.');
        abort_unless($order->fulfillment_status === 'pending', 422, 'This order is no longer awaiting a decision.');
        $data = $request->validate(['remark' => ['nullable', 'string', 'max:2000']]);

        $order->update([
            'fulfillment_status' => 'accepted',
            'fulfillment_remark' => $data['remark'] ?? null,
        ]);

        return redirect()->route('store.orders.show', $order)->with('status', "Order {$order->order_number} accepted.");
    }

    public function reject(Request $request, ShopOrder $order)
    {
        abort_unless($this->currentStore($request), 403, 'Your store must be approved to process orders.');
        abort_unless($order->fulfillment_status === 'pending', 422, 'This order is no longer awaiting a decision.');
        $data = $request->validate(['remark' => ['required', 'string', 'max:2000']]);

        $order->update([
            'fulfillment_status' => 'rejected',
            'fulfillment_remark' => $data['remark'],
            'captain_id' => null,
        ]);

        return redirect()->route('store.orders.show', $order)->with('status', "Order {$order->order_number} rejected.");
    }

    private function currentStore(Request $request): ?Store
    {
        $store = $request->user()->store;

        return ($store && $store->status === 'approved') ? $store : null;
    }
}
