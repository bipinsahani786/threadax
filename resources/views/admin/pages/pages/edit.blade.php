@extends('admin.layouts.app')
@section('title', 'Edit Page')
@section('page-title', 'Edit Page')
@section('content')
<form action="{{ route('admin.pages.update', $page) }}" method="POST" class="max-w-3xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Title *</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all font-mono">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Content *</label>
            <textarea name="body" rows="15" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all font-mono">{{ old('body', $page->body) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Active?</label>
                <select name="is_active" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
                    <option value="1" {{ $page->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$page->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta Description</label>
            <textarea name="meta_description" rows="2" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">{{ old('meta_description', $page->meta_description) }}</textarea>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <button type="submit" class="btn-primary py-3 px-8">Update Page</button>
        <a href="{{ route('admin.pages.index') }}" class="text-sm text-slate-500 hover:text-slate-900">Cancel</a>
    </div>
</form>
@endsection
