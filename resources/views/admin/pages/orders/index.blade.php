@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Orders ✨</h1>
        </div>
        
        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Filter button -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
                <select name="status" onchange="this.form.submit()" class="form-select bg-white border-slate-200 text-slate-500 hover:text-slate-600 rounded-md focus:ring-0">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Orders</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-lg rounded-sm border border-slate-200">
        <header class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">All Orders <span class="text-slate-400 font-medium">{{ $orders->total() }}</span></h2>
        </header>
        
        <div>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="font-semibold text-left">Order #</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Customer</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Total</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Status</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Date</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-200">
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-medium text-sky-500">{{ $order->order_number ?? 'TX-'.$order->id }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $order->user->name ?? 'Guest' }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="text-left font-medium text-emerald-500">₹{{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-xs capitalize
                                    @if($order->status === 'delivered') bg-emerald-100 text-emerald-600
                                    @elseif($order->status === 'cancelled') bg-rose-100 text-rose-500
                                    @elseif($order->status === 'processing') bg-sky-100 text-sky-600
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-600
                                    @else bg-amber-100 text-amber-600 @endif
                                ">
                                    {{ $order->status }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div>{{ $order->created_at->format('M d, Y H:i A') }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="space-x-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-slate-400 hover:text-slate-500 rounded-full">
                                        <span class="sr-only">View</span>
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                            <path d="M16 20c.3 0 .5-.1.7-.3l5.7-5.7-1.4-1.4-4 4V8h-2v8.6l-4-4L9.6 14l5.7 5.7c.2.2.4.3.7.3zM9 22h14v2H9z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-2 first:pl-5 last:pr-5 py-8 text-center text-slate-500">
                                No orders found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
