<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentTransaction::with(['order', 'user', 'payment'])->latest();

        // 1. Search Query (Transaction ID, Order Number, Customer Email, Customer Name)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%")
                         ->orWhere('shipping_name', 'like', "%{$search}%")
                         ->orWhere('shipping_email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Gateway Filter
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        // 4. Event Filter
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Calculate Executive Metrics
        $totalVolume      = PaymentTransaction::where('status', 'success')->sum('amount');
        $successCount     = PaymentTransaction::where('status', 'success')->count();
        $failedCount      = PaymentTransaction::where('status', 'failed')->count();
        $refundedVolume   = PaymentTransaction::where('status', 'refunded')->sum('amount');
        $refundedCount    = PaymentTransaction::where('status', 'refunded')->count();

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.pages.transactions.index', compact(
            'transactions',
            'totalVolume',
            'successCount',
            'failedCount',
            'refundedVolume',
            'refundedCount'
        ));
    }

    public function show($id)
    {
        $transaction = PaymentTransaction::with(['order', 'user', 'payment'])->findOrFail($id);
        return response()->json($transaction);
    }
}
