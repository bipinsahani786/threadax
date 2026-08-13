@extends('frontend.layouts.account')

@section('account_content')
    <div class="bg-white rounded-2xl border border-brand-border/60 shadow-sm overflow-hidden">
        {{-- Header Section --}}
        <div class="px-6 sm:px-8 py-6 border-b border-brand-border/60 bg-brand-light/30">
            <h2 class="text-xl font-heading font-black text-brand-dark uppercase tracking-tight">Profile Details</h2>
            <p class="text-sm text-brand-muted mt-1">Update your personal information and contact details.</p>
        </div>

        {{-- Form Section --}}
        <div class="p-6 sm:p-8">
            <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full pl-11 bg-brand-light/50 border border-brand-border rounded-xl px-4 py-3 text-brand-dark focus:outline-none focus:bg-white focus:border-brand-text focus:ring-1 focus:ring-brand-text transition-colors">
                    </div>
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Phone Field --}}
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 9876543210"
                            class="w-full pl-11 bg-brand-light/50 border border-brand-border rounded-xl px-4 py-3 text-brand-dark focus:outline-none focus:bg-white focus:border-brand-text focus:ring-1 focus:ring-brand-text transition-colors">
                    </div>
                    @error('phone') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Email Field (Disabled) --}}
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" disabled
                            class="w-full pl-11 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] font-medium text-brand-muted mt-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        Email address cannot be changed. Contact support for assistance.
                    </p>
                </div>

                <div class="pt-6 border-t border-brand-border/60 flex items-center justify-end">
                    <button type="submit" class="bg-brand-text text-white font-bold uppercase tracking-wider text-sm px-8 py-3.5 rounded-xl hover:bg-brand-dark hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-text focus:ring-offset-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
