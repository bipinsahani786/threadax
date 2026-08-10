@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-500 hover:text-indigo-600 mb-2 inline-block">&larr; Back to Orders</a>
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Order {{ $order->order_number ?? 'TX-'.$order->id }} ✨</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            <span class="inline-flex font-medium rounded-full text-center px-3 py-1 text-sm capitalize
                @if($order->status === 'delivered') bg-emerald-100 text-emerald-600
                @elseif($order->status === 'cancelled') bg-rose-100 text-rose-500
                @elseif($order->status === 'processing') bg-sky-100 text-sky-600
                @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-600
                @else bg-amber-100 text-amber-600 @endif
            ">
                {{ $order->status }}
            </span>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-sm text-sm font-medium bg-emerald-50 border border-emerald-200 text-emerald-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left column -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Items Table -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-800">Order Items</h2>
                </header>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Product</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-center">Price</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-center">Qty</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-right">Total</div></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 shrink-0 mr-2 sm:mr-3">
                                            @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                                                <img class="rounded object-cover w-full h-full" src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->name }}">
                                            @else
                                                <div class="w-full h-full rounded bg-slate-100"></div>
                                            @endif
                                        </div>
                                        <div class="font-medium text-slate-800">
                                            {{ $item->name }}
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                @if($item->color) {{ $item->color }} @endif
                                                @if($item->color && $item->size) | @endif
                                                @if($item->size) {{ $item->size }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-center">₹{{ number_format($item->unit_price, 2) }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-center">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-right text-emerald-500 font-medium">₹{{ number_format($item->total_price, 2) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Totals -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200">
                <div class="px-5 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-slate-500 text-sm">
                        Placed on {{ $order->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="w-full md:w-64 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal:</span>
                            <span class="font-medium text-slate-800">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Discount:</span>
                            <span class="font-medium text-emerald-500">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500">Shipping:</span>
                            <span class="font-medium text-slate-800">₹{{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-2 font-bold text-base">
                            <span class="text-slate-800">Total:</span>
                            <span class="text-emerald-500">₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Right column -->
        <div class="space-y-6">
            
            <!-- Actions -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-800">Order Actions</h2>
                </header>
                <div class="p-5">
                    <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium mb-1" for="status">Order Status</label>
                            <select id="status" name="status" class="form-select w-full">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="payment_status">Payment Status</label>
                            <select id="payment_status" name="payment_status" class="form-select w-full">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white w-full">Update Order</button>
                    </form>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-800">Customer Details</h2>
                </header>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase mb-1">Customer Info</div>
                        <div class="text-sm font-medium text-slate-800">{{ $order->user->name ?? 'Guest' }}</div>
                        <div class="text-sm text-slate-500">{{ $order->user->email ?? '' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase mb-1">Payment Method</div>
                        <div class="text-sm text-slate-800">
                            {{ $order->payment_method === 'razorpay' ? 'Razorpay (Online)' : 'Cash on Delivery' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-800">Shipping Address</h2>
                </header>
                <div class="p-5">
                    @if($order->address)
                        <address class="not-italic text-sm text-slate-600 space-y-1">
                            <span class="block font-medium text-slate-800">{{ $order->address->name }}</span>
                            <span class="block">{{ $order->address->line1 }}</span>
                            @if($order->address->line2) <span class="block">{{ $order->address->line2 }}</span> @endif
                            <span class="block">{{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->pincode }}</span>
                            <span class="block mt-2 font-medium">Phone: {{ $order->address->phone }}</span>
                        </address>
                    @else
                        <div class="text-sm text-slate-500">No address provided.</div>
                    @endif
                </div>
            </div>
            
        </div>
        
    </div>

</div>
@endsection
