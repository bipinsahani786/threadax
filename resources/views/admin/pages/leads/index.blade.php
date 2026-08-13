@extends('admin.layouts.app')

@section('title', 'Contact Leads')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Contact Leads</h1>
        <p class="text-sm text-gray-500 mt-1">Manage customer inquiries and follow-ups</p>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Follow Up</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-600 whitespace-nowrap">
                        {{ $lead->created_at->format('M d, Y g:i A') }}
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900">
                        {{ $lead->first_name }} {{ $lead->last_name }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $lead->email }}
                    </td>
                    <td class="py-4 px-6">
                        @if($lead->status === 'new')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">New</span>
                        @elseif($lead->status === 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">In Progress</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Resolved</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        @if($lead->follow_up_date)
                            @if($lead->follow_up_date->isPast() && $lead->status !== 'resolved')
                                <span class="text-red-600 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $lead->follow_up_date->format('M d, Y') }}
                                </span>
                            @else
                                {{ $lead->follow_up_date->format('M d, Y') }}
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('admin.leads.show', $lead->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            View / Update
                        </a>
                        <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this lead?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't received any contact submissions yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($leads->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $leads->links() }}
    </div>
    @endif
</div>
@endsection
