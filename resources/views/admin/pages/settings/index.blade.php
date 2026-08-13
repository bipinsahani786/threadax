@extends('admin.layouts.app')

@section('title', 'Global Settings')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Global Settings</h1>
</div>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
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

            <!-- Shipping & Returns Policy -->
            <div>
                <label for="shipping_policy" class="block text-sm font-medium text-gray-700">Shipping & Returns Policy</label>
                <textarea name="shipping_policy" id="shipping_policy" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter HTML or plain text...">{{ $settings['shipping_policy'] ?? "<p><strong class=\"text-brand-text\">Free Shipping:</strong> On all orders above ₹999.</p>\n<p><strong class=\"text-brand-text\">Delivery Time:</strong> Standard delivery within 3-5 business days. Metro cities within 1-2 business days.</p>\n<p><strong class=\"text-brand-text\">Returns:</strong> Easy 7-day returns and exchanges. Product must be unwashed and unworn with original tags attached.</p>" }}</textarea>
                <p class="text-xs text-gray-500 mt-1">This will be displayed on all product detail pages.</p>
            </div>

            <!-- Shop Header Image -->
            <div>
                <label for="shop_header_image" class="block text-sm font-medium text-gray-700">Shop Header Background Image</label>
                @if(isset($settings['shop_header_image']))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $settings['shop_header_image']) }}" alt="Shop Header" class="h-24 w-auto rounded border">
                    </div>
                @endif
                <input type="file" name="shop_header_image" id="shop_header_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 1920x600 pixels.</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
