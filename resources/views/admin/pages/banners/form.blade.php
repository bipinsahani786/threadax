@extends('admin.layouts.app')

@section('title', isset($banner) ? 'Edit Banner' : 'Add Banner')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($banner) ? 'Edit Banner' : 'Add Banner' }}</h1>
    <a href="{{ route('admin.banners.index') }}" class="text-gray-500 hover:text-gray-700">Back to Banners</a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($banner))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $banner->title ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Subtitle -->
            <div class="col-span-2">
                <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('subtitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Position <span class="text-red-500">*</span></label>
                <select name="position" id="position" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="hero" {{ old('position', $banner->position ?? '') == 'hero' ? 'selected' : '' }}>Hero (Top)</option>
                    <option value="mid" {{ old('position', $banner->position ?? '') == 'mid' ? 'selected' : '' }}>Mid Section</option>
                    <option value="bottom" {{ old('position', $banner->position ?? '') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                </select>
                @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order <span class="text-red-500">*</span></label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="text-xs text-gray-500 mt-1">Lower numbers show first</p>
                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Link -->
            <div>
                <label for="link" class="block text-sm font-medium text-gray-700">Link URL</label>
                <input type="text" name="link" id="link" value="{{ old('link', $banner->link ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="text-xs text-gray-500 mt-1">e.g., /products?category=men</p>
                @error('link') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Button Text -->
            <div>
                <label for="button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="text-xs text-gray-500 mt-1">e.g., Shop Now</p>
                @error('button_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Image -->
            <div class="col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">Banner Image {{ isset($banner) ? '' : '<span class="text-red-500">*</span>' }}</label>
                @if(isset($banner) && $banner->image_path)
                    <div class="mt-2 mb-4">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Current Image" class="h-32 object-contain bg-gray-100 rounded border">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" {{ isset($banner) ? '' : 'required' }} class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 1440x600 for Hero, 1440x300 for others.</p>
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Active Status -->
            <div class="col-span-2">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible on website)
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.banners.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">{{ isset($banner) ? 'Update Banner' : 'Save Banner' }}</button>
        </div>
    </form>
</div>
@endsection
