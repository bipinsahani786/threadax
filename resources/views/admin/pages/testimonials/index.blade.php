@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Testimonials</h1>
    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Testimonial
    </a>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Review (preview)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($testimonials as $testimonial)
            <tr>
                <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900">{{ $testimonial->name }}</div>
                    <div class="text-xs text-gray-500">{{ $testimonial->designation }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-0.5">
                        @for($i=1; $i<=5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </td>
                <td class="px-6 py-4 max-w-xs">
                    <p class="text-sm text-gray-600 truncate">{{ $testimonial->content }}</p>
                </td>
                <td class="px-6 py-4">
                    @if($testimonial->photo_url)
                        <span class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">📷 Photo</span>
                    @endif
                    @if($testimonial->video_url)
                        <span class="inline-flex items-center gap-1 text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">🎥 Video</span>
                    @endif
                    @if(!$testimonial->photo_url && !$testimonial->video_url)
                        <span class="text-gray-400 text-xs">Text only</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $testimonial->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $testimonial->is_active ? 'Active' : 'Hidden' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $testimonial->sort_order }}</td>
                <td class="px-6 py-4 text-sm flex items-center gap-3">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Delete this testimonial?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">No testimonials yet. <a href="{{ route('admin.testimonials.create') }}" class="text-blue-500 hover:underline">Add one</a>.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($testimonials->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $testimonials->links() }}</div>
    @endif
</div>
@endsection
