<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', '%' . $request->search . '%'));
            });
        }

        $orders = $query->paginate(20)->withQueryString();
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
            'status'         => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order = Order::with(['items.variant', 'payment'])->findOrFail($id);

        $isCancelling = $request->status === 'cancelled' && $order->status !== 'cancelled';

        DB::transaction(function () use ($order, $request, $isCancelling) {
            // ── Restore stock when cancelling ──────────────────────────────────
            if ($isCancelling) {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }

                Log::info("Stock restored for cancelled order #{$order->order_number}");
            }

            // ── Update Order Status ─────────────────────────────────────────────
            $order->update([
                'status'         => $request->status,
                'payment_status' => $request->payment_status,
            ]);

            // ── Trigger Razorpay Refund ─────────────────────────────────────────
            if ($isCancelling && $order->payment && $order->payment->isRefundable()) {
                try {
                    $refundId = $this->paymentService->initiateRefund($order->payment);

                    $order->payment->update([
                        'refund_id'     => $refundId,
                        'refund_status' => 'initiated',
                    ]);

                    Log::info("Razorpay refund initiated for order #{$order->order_number}", [
                        'refund_id' => $refundId,
                    ]);

                    session()->flash('info', 'Order cancelled. Razorpay refund of ₹' . number_format($order->payment->amount, 2) . ' has been initiated.');
                } catch (\Exception $e) {
                    Log::error("Razorpay auto-refund failed for order #{$order->order_number}: " . $e->getMessage());
                    session()->flash('warning', 'Order cancelled but automatic refund failed. Please process the refund manually from Razorpay Dashboard. Error: ' . $e->getMessage());
                }
            }
        });

        if (! session()->has('info') && ! session()->has('warning')) {
            session()->flash('success', 'Order status updated successfully.');
        }

        return redirect()->route('admin.orders.show', $order->id);
    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['items.variant.product', 'user', 'address'])->findOrFail($id);
        $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pages.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
