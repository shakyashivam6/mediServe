<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = ShopOrder::query()->with(['customer', 'captain'])->latest()->paginate(25);

        return view('Admin.orders.index', compact('orders'));
    }

    public function show(ShopOrder $order)
    {
        $order->load(['customer', 'captain']);

        return view('Admin.orders.show', compact('order'));
    }

    public function accept(Request $request, ShopOrder $order)
    {
        $data = $request->validate(['remark' => ['nullable', 'string', 'max:2000']]);
        $this->ensurePaymentCanBeProcessed($order);
        abort_if($order->fulfillment_status === 'rejected', 422, 'A rejected order cannot be accepted.');

        $order->update(['fulfillment_status' => 'accepted', 'fulfillment_remark' => $data['remark'] ?? null]);

        return redirect()->route('admin.orders.show', $order)->with('status', "Order {$order->order_number} accepted.");
    }

    public function reject(Request $request, ShopOrder $order)
    {
        $data = $request->validate(['remark' => ['required', 'string', 'max:2000']]);
        $this->ensurePaymentCanBeProcessed($order);
        abort_if($order->fulfillment_status === 'accepted', 422, 'An accepted order cannot be rejected.');

        $order->update([
            'fulfillment_status' => 'rejected',
            'fulfillment_remark' => $data['remark'],
            'captain_id' => null,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('status', "Order {$order->order_number} rejected.");
    }

    private function ensurePaymentCanBeProcessed(ShopOrder $order): void
    {
        abort_unless($order->payment_method === 'cod' || $order->payment_status === 'paid', 422, 'This order cannot be processed until payment is confirmed.');
    }
}
