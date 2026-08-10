@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Overview')

@section('content')

{{-- Welcome banner --}}
<div class="relative overflow-hidden rounded-[24px] p-8 sm:p-10 mb-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-gradient-to-bl from-white/10 to-transparent blur-2xl"></div>
    <div class="absolute bottom-0 right-32 mb-[-10%] w-40 h-40 rounded-full bg-gradient-to-tr from-brand-text/20 to-transparent blur-xl"></div>
    
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-4">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wide text-white/90">System Online</span>
            </div>
            <h3 class="text-3xl font-extrabold mb-3 font-heading tracking-tight text-white">
                Welcome back, {{ explode(' ', Auth::guard('admin')->user()->name ?? 'Admin')[0] }}! 👋
            </h3>
            <p class="text-white/60 text-sm font-medium max-w-xl leading-relaxed">
                Here's what's happening with your store today. Manage your products, monitor sales, and check customer activity from your control center.
            </p>
        </div>
        <div class="shrink-0 flex gap-3 w-full sm:w-auto">
            <a href="#" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-slate-900 bg-white hover:bg-slate-50 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_25px_rgba(255,255,255,0.5)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Total Orders --}}
    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <p class="text-xs text-green-500 font-bold mt-0.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +12%
                </p>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading tracking-tight">{{ number_format($stats['total_orders']) }}</p>
    </div>

    {{-- Revenue --}}
    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 text-green-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Revenue</span>
                <p class="text-xs text-green-500 font-bold mt-0.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +8.4%
                </p>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading tracking-tight">₹{{ number_format($stats['total_revenue']) }}</p>
    </div>

    {{-- Products --}}
    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-purple-50 to-purple-100 text-purple-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Products</span>
                <p class="text-xs text-slate-400 font-bold mt-0.5 flex items-center gap-1">
                    In catalog
                </p>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading tracking-tight">{{ number_format($stats['total_products']) }}</p>
    </div>

    {{-- Customers --}}
    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-pink-50 to-pink-100 text-pink-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Customers</span>
                <p class="text-xs text-green-500 font-bold mt-0.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    New users
                </p>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading tracking-tight">{{ number_format($stats['total_customers']) }}</p>
    </div>

</div>

@endsection
