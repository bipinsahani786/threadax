@extends('admin.layouts.app')

@section('title', 'Global Settings')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Global Settings</h1>
</div>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Contact Email -->
            <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" value="{{ $settings['contact_email'] ?? 'support@threadax.co.in' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- WhatsApp Number -->
            <div>
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="text-xs text-gray-500 mt-1">Include country code, e.g., 919876543210</p>
            </div>

            <!-- Free Shipping Threshold -->
            <div>
                <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700">Free Shipping Threshold (₹)</label>
                <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? '999' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Instagram Link -->
            <div>
                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram Link</label>
                <input type="url" name="instagram_link" id="instagram_link" value="{{ $settings['instagram_link'] ?? 'https://instagram.com/threadax.co.in' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Facebook Link -->
            <div>
                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook Link</label>
                <input type="url" name="facebook_link" id="facebook_link" value="{{ $settings['facebook_link'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Store Address -->
            <div>
                <label for="store_address" class="block text-sm font-medium text-gray-700">Store Address</label>
                <textarea name="store_address" id="store_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $settings['store_address'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
