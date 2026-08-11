@extends('admin.layouts.app')

@section('title', 'Manage Banners')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn-primary">Add Banner</a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position & Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($banners as $banner)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="h-16 w-16 object-cover rounded">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                    @if($banner->subtitle)
                        <div class="text-sm text-gray-500">{{ $banner->subtitle }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 capitalize">{{ $banner->position }}</div>
                    <div class="text-sm text-gray-500">Order: {{ $banner->sort_order }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                    No banners found. <a href="{{ route('admin.banners.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
