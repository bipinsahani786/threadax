<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);
        return view('admin.pages.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.variant.product', 'user', 'address', 'payment'])->findOrFail($id);
        return view('admin.pages.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order updated successfully.');
    }
}
