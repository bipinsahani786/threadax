@extends('admin.layouts.app')

@section('title', 'Lead Details: ' . $lead->first_name)

@section('header')
<div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.leads.index') }}" class="text-gray-400 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lead Details</h1>
            <p class="text-sm text-gray-500 mt-1">Submitted on {{ $lead->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Lead Info --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Customer Message</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="mt-1 text-base text-gray-900 font-bold">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email Address</p>
                        <a href="mailto:{{ $lead->email }}" class="mt-1 text-base text-blue-600 font-bold hover:underline">{{ $lead->email }}</a>
                    </div>
                    @if($lead->order_number)
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-500">Related Order Number</p>
                        <p class="mt-1 text-base text-gray-900 bg-gray-100 inline-block px-3 py-1 rounded font-mono">{{ $lead->order_number }}</p>
                    </div>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 mb-3">Message Content</p>
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 text-gray-800 whitespace-pre-wrap leading-relaxed">
                        {{ $lead->message }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Actions --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Update Lead</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ $lead->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $lead->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Follow-up Date</label>
                        <input type="date" name="follow_up_date" value="{{ $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Internal Notes</label>
                        <textarea name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Add private notes for your team...">{{ $lead->notes }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
