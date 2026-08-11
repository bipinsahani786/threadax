@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Reviews</h1>
            <p class="text-sm text-gray-500 mt-1">Manage customer reviews and ratings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm rounded">{{ session('success') }}</div>
    @endif

    {{-- Tabs --}}
    <div class="flex space-x-4 border-b border-gray-200">
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="py-2 px-4 text-sm font-medium {{ $status === 'pending' ? 'text-brand-text border-b-2 border-brand-text' : 'text-gray-500 hover:text-gray-700' }}">Pending</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="py-2 px-4 text-sm font-medium {{ $status === 'approved' ? 'text-brand-text border-b-2 border-brand-text' : 'text-gray-500 hover:text-gray-700' }}">Approved</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="py-2 px-4 text-sm font-medium {{ $status === 'rejected' ? 'text-brand-text border-b-2 border-brand-text' : 'text-gray-500 hover:text-gray-700' }}">Rejected</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'all']) }}" class="py-2 px-4 text-sm font-medium {{ $status === 'all' ? 'text-brand-text border-b-2 border-brand-text' : 'text-gray-500 hover:text-gray-700' }}">All</a>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">User</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Rating</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Review</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Date</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 uppercase tracking-wider text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($review->product->primaryImage)
                                    <img src="{{ $review->product->primaryImage->url }}" class="w-10 h-10 rounded object-cover">
                                @endif
                                <a href="{{ route('frontend.products.show', $review->product->slug) }}" target="_blank" class="font-medium text-gray-900 hover:underline line-clamp-2">
                                    {{ $review->product->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $review->user->name }}</td>
                        <td class="px-6 py-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->title)
                                <p class="font-bold text-gray-900 text-xs mb-1">{{ $review->title }}</p>
                            @endif
                            <p class="text-gray-600 text-xs line-clamp-3">{{ $review->body }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $review->created_at->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($review->status !== 'approved')
                                <form method="POST" action="{{ route('admin.reviews.status.update', $review) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium">Approve</button>
                                </form>
                            @endif
                            @if($review->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.reviews.status.update', $review) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No {{ $status !== 'all' ? $status : '' }} reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
        @endif
    </div>
</div>
@endsection
