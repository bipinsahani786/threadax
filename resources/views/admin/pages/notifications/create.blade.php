@extends('admin.layouts.app')

@section('title', 'Send Notification')

@section('header')
<h2 class="text-xl font-bold text-gray-800">Send Push Notification</h2>
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Notification Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. MEGA SALE: Flat 50% Off!">
                    <p class="text-xs text-gray-500 mt-1">Keep it short and catchy.</p>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message Body <span class="text-red-500">*</span></label>
                    <textarea name="message" id="message" rows="3" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Get 50% off on all winter wear. Use code WINTER50. Limited time offer!"></textarea>
                </div>

                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Call to Action Link (Optional)</label>
                    <input type="url" name="link" id="link" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://threadax.com/products?category=winter">
                    <p class="text-xs text-gray-500 mt-1">URL where the user should be redirected when they click the notification.</p>
                </div>
                
                <div>
                    <label for="target" class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                    <select name="target" id="target" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="all_users">All Customers</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        Send Notification
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
