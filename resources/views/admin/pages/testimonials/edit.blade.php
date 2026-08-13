@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.testimonials.index') }}" class="text-gray-500 hover:text-gray-900">← Back</a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Testimonial</h1>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="max-w-2xl space-y-6">
    @csrf @method('PUT')

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Customer Name *</label>
                <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Designation</label>
                <input type="text" name="designation" value="{{ old('designation', $testimonial->designation) }}" placeholder="e.g. Verified Buyer" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Review *</label>
            <textarea name="content" rows="4" required maxlength="1000" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">{{ old('content', $testimonial->content) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Rating *</label>
                <select name="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
                    @for($i=5; $i>=1; $i--)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
            </div>
        </div>

        <hr class="border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase tracking-widest">Media (Optional)</p>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Customer Photo URL</label>
            <input type="url" name="photo_url" value="{{ old('photo_url', $testimonial->photo_url) }}" placeholder="https://..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Video URL (YouTube or MP4)</label>
            <input type="url" name="video_url" value="{{ old('video_url', $testimonial->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-slate-900 focus:ring-slate-400">
            <label for="is_active" class="text-sm font-semibold text-gray-700">Show on Homepage</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-slate-900 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">Update Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-semibold px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</a>
    </div>
</form>
@endsection
