@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white rounded-2xl border border-brand-border/60 shadow-sm overflow-hidden">
    <div class="px-6 sm:px-8 py-6 border-b border-brand-border/60 bg-brand-light/30 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-heading font-black text-brand-dark uppercase tracking-tight">Notifications</h2>
            <p class="text-sm text-brand-muted mt-1">Updates on your orders and exclusive offers.</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('account.notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-brand-text hover:underline uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="divide-y divide-brand-border/40">
            @foreach($notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}" class="block p-6 sm:p-8 hover:bg-brand-light/50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }} group">
                    <div class="flex gap-4 sm:gap-6">
                        <div class="shrink-0 mt-1">
                            @if(($notification->data['type'] ?? '') === 'offer')
                                <span class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                </span>
                            @else
                                <span class="w-12 h-12 rounded-full bg-brand-light text-brand-dark flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-base font-bold text-brand-dark truncate {{ is_null($notification->read_at) ? '' : 'text-brand-muted' }}">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                @if(is_null($notification->read_at))
                                    <span class="inline-block w-2 h-2 rounded-full bg-brand-text shrink-0"></span>
                                @endif
                            </div>
                            <p class="text-sm text-brand-muted mb-2 leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[11px] font-bold text-brand-muted/70 uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }} · {{ $notification->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($notifications->hasPages())
            <div class="p-6 border-t border-brand-border/60">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="px-6 py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-brand-light flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-brand-muted/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
            </div>
            <h4 class="font-heading font-black text-xl text-brand-dark mb-2">All Caught Up!</h4>
            <p class="text-sm text-brand-muted">You have no notifications right now.</p>
        </div>
    @endif
</div>
@endsection
