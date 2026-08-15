<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        $searched = false;

        if ($request->filled('order_number')) {
            $searched = true;
            $orderNumber = trim($request->order_number);
            $contact = trim($request->contact ?? '');

            $query = Order::with([
                'items.variant.product.images',
                'address',
                'payment',
                'trackings' => fn($q) => $q->orderBy('event_time', 'desc')->orderBy('id', 'desc'),
            ])->where(function($q) use ($orderNumber) {
                $q->where('order_number', $orderNumber)
                  ->orWhere('tracking_number', $orderNumber)
                  ->orWhere('shiprocket_awb_code', $orderNumber);
            });

            if (!empty($contact)) {
                $query->where(function($q) use ($contact) {
                    $q->where('shipping_phone', 'like', "%{$contact}%")
                      ->orWhere('shipping_email', 'like', "%{$contact}%")
                      ->orWhereHas('user', function($u) use ($contact) {
                          $u->where('email', 'like', "%{$contact}%")
                            ->orWhere('phone', 'like', "%{$contact}%");
                      })
                      ->orWhereHas('address', function($a) use ($contact) {
                          $a->where('phone', 'like', "%{$contact}%");
                      });
                });
            }

            $order = $query->first();
        }

        return view('frontend.pages.tracking', compact('order', 'searched'));
    }
}
