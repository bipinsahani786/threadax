@extends('admin.layouts.app')

@section('title', 'Contact Leads & Inquiries')
@section('page-title', 'Contact Leads')

@section('content')

<div x-data="{
    detailModalOpen: false,
    activeTab: 'details', // 'details' or 'reply'
    activeLead: null,
    statusForm: {
        status: 'new',
        follow_up_date: '',
        notes: ''
    },
    replyForm: {
        subject: '',
        reply_message: '',
        mark_status: 'in_progress',
        sending: false
    },
    openLead(lead) {
        this.activeLead = lead;
        this.activeTab = 'details';
        this.statusForm.status = lead.status;
        this.statusForm.follow_up_date = lead.follow_up_date ? lead.follow_up_date.substring(0, 10) : '';
        this.statusForm.notes = lead.notes || '';
        this.replyForm.subject = 'ThreadAx Support — Re: ' + (lead.order_number ? 'Order #' + lead.order_number : 'Customer Inquiry');
        this.replyForm.reply_message = 'Hi ' + lead.first_name + ',\n\nThank you for reaching out to ThreadAx Support.\n\n';
        this.replyForm.mark_status = 'in_progress';
        this.replyForm.sending = false;
        this.detailModalOpen = true;
    },
    getCleanPhone(phone) {
        if (!phone) return '';
        let clean = phone.replace(/[^0-9]/g, '');
        if (clean.length === 10) clean = '91' + clean;
        return clean;
    }
}">

    {{-- 1. TOP HEADER & OVERVIEW BANNER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>📬 Customer Support & Inquiries</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Track, respond, and follow up with customer support inquiries, feedback, and order questions.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leads.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                <span>Refresh Leads</span>
            </a>
        </div>
    </div>

    {{-- 2. ANALYTICS STAT CARDS (CLICKABLE FILTERS) --}}
    @php
        $currentStatus = request('status', '');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4 mb-6">
        
        {{-- Card 1: All Inquiries --}}
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['status', 'page']), [])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ empty($currentStatus) ? 'bg-slate-900 text-white border-slate-900 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-slate-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ empty($currentStatus) ? 'text-slate-300' : 'text-slate-400' }}">Total Leads</span>
                <span class="text-sm">📨</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['total']) }}</p>
            <p class="text-[10px] mt-1 {{ empty($currentStatus) ? 'text-slate-300' : 'text-slate-400' }}">{{ $stats['today'] }} received today</p>
        </a>

        {{-- Card 2: New Inquiries --}}
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['status', 'page']), ['status' => 'new'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'new' ? 'bg-blue-600 text-white border-blue-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-blue-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'new' ? 'text-blue-100' : 'text-blue-600' }}">New Inquiries</span>
                <span class="w-2 h-2 rounded-full bg-blue-400 {{ $stats['new'] > 0 ? 'animate-ping' : '' }}"></span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['new']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'new' ? 'text-blue-100' : 'text-slate-400' }}">Awaiting response</p>
        </a>

        {{-- Card 3: In Progress --}}
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'in_progress' ? 'bg-amber-600 text-white border-amber-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-amber-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'in_progress' ? 'text-amber-100' : 'text-amber-600' }}">In Progress</span>
                <span class="text-sm">⏳</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['in_progress']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'in_progress' ? 'text-amber-100' : 'text-slate-400' }}">Active resolution</p>
        </a>

        {{-- Card 4: Resolved --}}
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['status', 'page']), ['status' => 'resolved'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'resolved' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-emerald-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'resolved' ? 'text-emerald-100' : 'text-emerald-600' }}">Resolved</span>
                <span class="text-sm">✓</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['resolved']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'resolved' ? 'text-emerald-100' : 'text-slate-400' }}">Closed inquiries</p>
        </a>

        {{-- Card 5: Overdue Follow-ups --}}
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['status', 'page']), ['status' => 'overdue'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'overdue' ? 'bg-rose-600 text-white border-rose-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-rose-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'overdue' ? 'text-rose-100' : 'text-rose-600' }}">Needs Follow-up</span>
                <span class="text-sm">⚠️</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['overdue']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'overdue' ? 'text-rose-100' : 'text-slate-400' }}">Due / Overdue</p>
        </a>

    </div>

    {{-- 3. SEARCH & ADVANCED DATE FILTERS TOOLBAR --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 mb-6 shadow-xs">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="space-y-4">
            
            {{-- Search + Status Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                
                {{-- Search Box (col-span-5) --}}
                <div class="sm:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search name, email, phone, order #, message..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                </div>

                {{-- Status Select (col-span-3) --}}
                <div class="sm:col-span-3">
                    <select name="status" class="w-full py-2.5 px-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-semibold text-slate-900 outline-none transition-all">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>🔴 New Inquiries</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>🟢 Resolved</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>⚠️ Overdue Follow-up</option>
                    </select>
                </div>

                {{-- Date From & To (col-span-4) --}}
                <div class="sm:col-span-4 grid grid-cols-2 gap-2">
                    <div class="relative">
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}" 
                               title="From Date"
                               class="w-full py-2.5 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 outline-none">
                    </div>
                    <div class="relative">
                        <input type="date" 
                               name="date_to" 
                               value="{{ request('date_to') }}" 
                               title="To Date"
                               class="w-full py-2.5 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 outline-none">
                    </div>
                </div>

            </div>

            {{-- Quick Date Presets & Filter Actions Row --}}
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
                
                {{-- Presets --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Presets:</span>
                    <button type="submit" name="preset" value="today" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === 'today' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Today
                    </button>
                    <button type="submit" name="preset" value="7_days" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === '7_days' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Last 7 Days
                    </button>
                    <button type="submit" name="preset" value="30_days" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === '30_days' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Last 30 Days
                    </button>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'preset']))
                        <a href="{{ route('admin.leads.index') }}" class="px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-xs active:scale-95 cursor-pointer">
                        Apply Filters
                    </button>
                </div>

            </div>

        </form>
    </div>

    {{-- 4. CONTACT LEADS TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        @if($leads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Customer Details</th>
                            <th class="py-3.5 px-4 sm:px-6">Phone / WhatsApp</th>
                            <th class="py-3.5 px-4 sm:px-6">Order Ref</th>
                            <th class="py-3.5 px-4 sm:px-6">Status</th>
                            <th class="py-3.5 px-4 sm:px-6">Follow-up</th>
                            <th class="py-3.5 px-4 sm:px-6">Received</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($leads as $lead)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                
                                {{-- Customer Info + Message Snippet --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                                            {{ strtoupper(substr($lead->first_name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-sm leading-tight flex items-center gap-1.5">
                                                <span>{{ $lead->first_name }} {{ $lead->last_name }}</span>
                                                @if($lead->status === 'new')
                                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                                @endif
                                            </p>
                                            <a href="mailto:{{ $lead->email }}" class="text-[11px] text-slate-500 hover:text-slate-900 hover:underline block truncate max-w-[200px]">
                                                {{ $lead->email }}
                                            </a>
                                            <p class="text-[11px] text-slate-600 line-clamp-1 mt-1 font-normal bg-slate-50 px-2 py-0.5 rounded border border-slate-100 inline-block max-w-[280px]">
                                                "{{ $lead->message }}"
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Customer Phone & WhatsApp --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    @if(!empty($lead->phone))
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $lead->phone);
                                            if(strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                                        @endphp
                                        <div class="flex items-center gap-1.5">
                                            <a href="tel:{{ $lead->phone }}" class="font-bold text-slate-800 hover:text-slate-950 hover:underline flex items-center gap-1">
                                                <span>📞</span>
                                                <span>{{ $lead->phone }}</span>
                                            </a>
                                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi ' . $lead->first_name . ', regarding your inquiry on ThreadAx...') }}" 
                                               target="_blank" 
                                               title="Chat on WhatsApp"
                                               class="p-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>

                                {{-- Order Number --}}
                                <td class="py-4 px-4 sm:px-6">
                                    @if(!empty($lead->order_number))
                                        <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 text-xs">
                                            #{{ $lead->order_number }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">General Support</span>
                                    @endif
                                </td>

                                {{-- Status Pill --}}
                                <td class="py-4 px-4 sm:px-6">
                                    @if($lead->status === 'new')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>New Inquiry</span>
                                        </span>
                                    @elseif($lead->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>In Progress</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Resolved</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Follow Up Date --}}
                                <td class="py-4 px-4 sm:px-6">
                                    @if($lead->follow_up_date)
                                        @php
                                            $isOverdue = $lead->follow_up_date->isPast() && $lead->status !== 'resolved';
                                        @endphp
                                        <span class="inline-flex items-center gap-1 font-bold text-xs {{ $isOverdue ? 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200' : 'text-slate-700' }}">
                                            @if($isOverdue) ⚠️ @endif
                                            <span>{{ $lead->follow_up_date->format('M d, Y') }}</span>
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                {{-- Received Timestamp --}}
                                <td class="py-4 px-4 sm:px-6 text-slate-500 whitespace-nowrap">
                                    <span class="block font-medium text-slate-700">{{ $lead->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $lead->created_at->format('h:i A') }}</span>
                                </td>

                                {{-- Actions (Quick Modal & Delete) --}}
                                <td class="py-4 px-4 sm:px-6 text-right space-x-1.5 whitespace-nowrap">
                                    <button type="button" 
                                            @click="openLead({{ json_encode($lead) }})"
                                            class="inline-flex items-center gap-1 font-bold text-slate-900 bg-slate-100 hover:bg-slate-900 hover:text-white px-3 py-1.5 rounded-xl transition-all shadow-2xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Manage</span>
                                    </button>

                                    <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Permanently delete this contact inquiry?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer" title="Delete Inquiry">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($leads->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/50">
                    {{ $leads->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    📬
                </div>
                <h4 class="font-heading font-bold text-sm text-slate-900 mb-1">No Leads Found</h4>
                <p class="text-xs text-slate-400 mb-4 max-w-sm mx-auto">No customer inquiries match your current search or date filters.</p>
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-xs">
                    Reset All Filters
                </a>
            </div>
        @endif
    </div>

    {{-- 5. QUICK INQUIRY DETAILS, WHATSAPP CHAT & EMAIL REPLY MODAL --}}
    <div x-cloak x-show="detailModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-12" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="detailModalOpen = false"></div>

        <div class="relative mx-auto max-w-2xl rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            
            <template x-if="activeLead">
                <div>
                    {{-- Modal Header --}}
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-slate-950 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                <span x-text="activeLead.first_name ? activeLead.first_name.charAt(0).toUpperCase() : 'C'"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-heading font-extrabold text-slate-900" x-text="activeLead.first_name + ' ' + (activeLead.last_name || '')"></h3>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-slate-500">
                                    <span x-text="activeLead.email"></span>
                                    <template x-if="activeLead.phone">
                                        <span>• 📞 <span x-text="activeLead.phone" class="font-bold text-slate-800"></span></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false" class="p-2 text-slate-400 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Quick Action Buttons Bar --}}
                    <div class="px-5 sm:px-6 py-3 bg-slate-100/60 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- WhatsApp Redirect Button --}}
                            <template x-if="activeLead.phone">
                                <a :href="'https://wa.me/' + getCleanPhone(activeLead.phone) + '?text=' + encodeURIComponent('Hi ' + activeLead.first_name + ', replying regarding your ThreadAx inquiry: ' + activeLead.message)" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-all shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    <span>Chat on WhatsApp</span>
                                </a>
                            </template>

                            {{-- Direct Call Button --}}
                            <template x-if="activeLead.phone">
                                <a :href="'tel:' + activeLead.phone" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-800 font-bold hover:bg-slate-50 transition-all shadow-2xs">
                                    <span>📞 Call Now</span>
                                </a>
                            </template>

                            {{-- Open Mail App (mailto) --}}
                            <a :href="'mailto:' + activeLead.email + '?subject=' + encodeURIComponent('ThreadAx Support - Regarding Inquiry') + '&body=' + encodeURIComponent('\n\n--- Customer Message ---\n' + activeLead.message)" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-800 font-bold hover:bg-slate-50 transition-all shadow-2xs">
                                <span>✉️ Open in Mail Client</span>
                            </a>
                        </div>

                        {{-- Modal Tabs Switcher --}}
                        <div class="flex items-center p-0.5 rounded-xl bg-slate-200/80">
                            <button type="button" 
                                    @click="activeTab = 'details'" 
                                    :class="activeTab === 'details' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 font-medium'"
                                    class="px-3 py-1 rounded-lg text-xs transition-all">
                                📝 Inquiry Details
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'reply'" 
                                    :class="activeTab === 'reply' ? 'bg-slate-900 text-white font-bold shadow-xs' : 'text-slate-600 font-medium'"
                                    class="px-3 py-1 rounded-lg text-xs transition-all flex items-center gap-1">
                                <span>🚀 Reply by Email</span>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 sm:p-6 space-y-5 text-xs">
                        
                        {{-- Meta Details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Order Reference</span>
                                <span class="font-bold text-slate-900 mt-0.5 block" x-text="activeLead.order_number ? '#' + activeLead.order_number : 'General Support'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                                <span class="font-bold text-slate-900 mt-0.5 block" x-text="activeLead.phone || 'Not Provided'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Current Status</span>
                                <span class="font-bold uppercase tracking-wider text-[11px] mt-0.5 block" 
                                      :class="activeLead.status === 'new' ? 'text-blue-600' : (activeLead.status === 'in_progress' ? 'text-amber-600' : 'text-emerald-600')" 
                                      x-text="activeLead.status.replace('_', ' ')"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Received On</span>
                                <span class="font-bold text-slate-900 mt-0.5 block" x-text="new Date(activeLead.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })"></span>
                            </div>
                        </div>

                        {{-- TAB 1: INQUIRY DETAILS & STATUS UPDATE --}}
                        <div x-show="activeTab === 'details'" class="space-y-4">
                            {{-- Customer Message Text --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Customer Message</label>
                                <div class="p-4 rounded-2xl bg-slate-100/70 border border-slate-200/80 text-slate-900 text-xs font-normal leading-relaxed whitespace-pre-wrap" x-text="activeLead.message">
                                </div>
                            </div>

                            {{-- Update Status & Follow-Up Form --}}
                            <form :action="'/admin/leads/' + activeLead.id" method="POST" class="pt-3 border-t border-slate-100 space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Status Dropdown --}}
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Inquiry Status</label>
                                        <select name="status" x-model="statusForm.status" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 outline-none">
                                            <option value="new">🔴 New Inquiry</option>
                                            <option value="in_progress">🟡 In Progress</option>
                                            <option value="resolved">🟢 Resolved</option>
                                        </select>
                                    </div>

                                    {{-- Follow Up Date --}}
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Next Follow-up Date</label>
                                        <input type="date" name="follow_up_date" x-model="statusForm.follow_up_date" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                                    </div>
                                </div>

                                {{-- Internal Notes --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Internal Notes & History</label>
                                    <textarea name="notes" x-model="statusForm.notes" rows="3" placeholder="Add resolution notes, customer conversation summary..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none"></textarea>
                                </div>

                                {{-- Modal Footer Actions --}}
                                <div class="flex items-center justify-end gap-2 pt-2">
                                    <button type="button" @click="detailModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all cursor-pointer">
                                        Save Status & Notes
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- TAB 2: IN-APP DIRECT EMAIL REPLY COMPOSER --}}
                        <div x-show="activeTab === 'reply'" class="space-y-4">
                            <form :action="'/admin/leads/' + activeLead.id + '/reply'" method="POST" class="space-y-4">
                                @csrf

                                {{-- Recipient Info Pill --}}
                                <div class="p-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="text-blue-600 font-bold">To:</span>
                                        <span class="font-bold text-slate-900" x-text="activeLead.first_name + ' ' + (activeLead.last_name || '')"></span>
                                        <span class="text-slate-500 font-mono" x-text="'<' + activeLead.email + '>'"></span>
                                    </div>
                                    <span class="text-[10px] text-blue-700 font-bold bg-blue-100/70 px-2 py-0.5 rounded-md">Direct SMTP Mail</span>
                                </div>

                                {{-- Email Subject --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Subject <span class="text-rose-500">*</span></label>
                                    <input type="text" 
                                           name="subject" 
                                           x-model="replyForm.subject" 
                                           required
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-slate-400">
                                </div>

                                {{-- Reply Message Body --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reply Message <span class="text-rose-500">*</span></label>
                                    <textarea name="reply_message" 
                                              x-model="replyForm.reply_message" 
                                              rows="6" 
                                              required
                                              placeholder="Type your response to the customer..." 
                                              class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-slate-400 font-sans leading-relaxed"></textarea>
                                </div>

                                {{-- Status After Reply --}}
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-700">Update Inquiry Status After Sending:</span>
                                    <div class="flex items-center gap-3">
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 font-medium cursor-pointer">
                                            <input type="radio" name="mark_status" value="in_progress" x-model="replyForm.mark_status" class="text-slate-900">
                                            <span>In Progress</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 font-medium cursor-pointer">
                                            <input type="radio" name="mark_status" value="resolved" x-model="replyForm.mark_status" class="text-slate-900">
                                            <span>Resolved</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Reply Actions --}}
                                <div class="flex items-center justify-end gap-2 pt-2">
                                    <button type="button" @click="activeTab = 'details'" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                                        Back to Details
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                        <span>Send Email Reply Now</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </template>

        </div>
    </div>

</div>

@endsection
