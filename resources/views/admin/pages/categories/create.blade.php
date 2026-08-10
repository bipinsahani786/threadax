@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <span>Create Category</span>
    </div>
@endsection

@section('content')

<div class="max-w-4xl">
    <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-8">
            @csrf
            
            {{-- Form Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Left Column --}}
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Category Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('name') border-red-500 @enderror"
                               placeholder="e.g., Men's T-Shirts">
                        @error('name') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-bold text-slate-900 mb-2">Parent Category</label>
                        <select id="parent_id" name="parent_id" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('parent_id') border-red-500 @enderror bg-white">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-500">Leave blank to create a top-level category.</p>
                        @error('parent_id') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    <div>
                        <label for="sort_order" class="block text-sm font-bold text-slate-900 mb-2">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('sort_order') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500">Lower numbers appear first in lists.</p>
                        @error('sort_order') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <input type="hidden" name="is_active" value="0">
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-slate-200 appearance-none cursor-pointer transition-transform duration-200 ease-in-out checked:border-slate-900 checked:translate-x-6 z-10"/>
                            <label for="is_active" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                        </div>
                        <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Active</label>
                    </div>
                    <p class="text-xs text-slate-500 -mt-2 ml-[60px]">Inactive categories are hidden from the store.</p>
                </div>
            </div>

            {{-- Full Width --}}
            <div>
                <label for="description" class="block text-sm font-bold text-slate-900 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('description') border-red-500 @enderror"
                          placeholder="Brief description for SEO and category headers...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-shadow shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_16px_rgba(0,0,0,0.15)]">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    /* Custom Toggle Switch Styles */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #0F172A; /* slate-900 */
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #0F172A;
    }
</style>
@endpush
@endsection
