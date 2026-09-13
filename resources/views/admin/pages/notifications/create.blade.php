@extends('admin.layouts.app')

@section('title', 'Push Notifications Studio - Admin')
@section('page-title', 'Broadcast Studio & Push Notifications')

@section('content')
<div class="space-y-6 sm:space-y-8"
     x-data="{
         title: '🔥 NEW DROP ALERT: Oversized Heavyweight Tees',
         message: 'Drop 04 is now live. Premium 280 GSM French Terry Cotton fits in limited quantities. Claim yours before it sells out!',
         link: '{{ url('/shop') }}',
         target: 'all_users',

         setTemplate(tTitle, tMsg, tLink) {
             this.title = tTitle;
             this.message = tMsg;
             if (tLink) this.link = tLink;
         }
     }">

    {{-- Executive Audience Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Reach --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Customers</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg">
                    👥
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Total Users
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">All registered customer accounts</p>
        </div>

        {{-- Active Buyers --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Buyers</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    🛍️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['buyers']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Buyers
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Customers with completed orders</p>
        </div>

        {{-- Repeat VIPs --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">VIP Repeat Buyers</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    🔁
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['repeat']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    VIPs (≥ 2 orders)
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">High lifetime-value customers</p>
        {{-- Push Devices (Firebase FCM) --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Web Push Devices</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    📱
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-blue-950">{{ number_format($stats['push_devices'] ?? 0) }}</span>
                @if($stats['fcm_configured'])
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        🟢 FCM Active
                    </span>
                @else
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                        Setup .env
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-500 font-medium">
                <span class="font-bold text-slate-700">📱 {{ $stats['mobile_devices'] ?? 0 }} Phones</span>
                <span>•</span>
                <span>💻 {{ $stats['desktop_devices'] ?? 0 }} Desktop</span>
            </div>
        </div>

    </div>

    {{-- Form & Simulator Studio Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        {{-- Broadcast Composer (2/3 width) --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-6">
                
                {{-- Card Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            📢
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Broadcast Campaign Composer</h3>
                            <p class="text-xs text-slate-400">Compose and dispatch in-app and push notification alerts</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Campaign Templates --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        ⚡ Quick Campaign Presets
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button" 
                                @click="setTemplate('🔥 NEW DROP: Cyber Acid Wash Collection', 'Drop 04 is now officially live. Heavyweight 280 GSM oversized streetwear. Limited quantities available.', '{{ url('/shop') }}')"
                                class="p-2.5 rounded-xl border border-slate-200 hover:border-slate-400 bg-slate-50/50 hover:bg-slate-100 text-left transition-colors cursor-pointer">
                            <span class="block text-xs font-extrabold text-slate-900">🔥 New Drop Launch</span>
                            <span class="text-[11px] text-slate-400 line-clamp-1">Heavyweight acid wash collection</span>
                        </button>

                        <button type="button" 
                                @click="setTemplate('🎁 VIP 20% OFF: Exclusive Weekend Flash', 'Enjoy flat 20% off on all streetwear orders this weekend. Use code VIP20 at checkout.', '{{ url('/shop') }}')"
                                class="p-2.5 rounded-xl border border-slate-200 hover:border-slate-400 bg-slate-50/50 hover:bg-slate-100 text-left transition-colors cursor-pointer">
                            <span class="block text-xs font-extrabold text-slate-900">🎁 VIP Flash Sale</span>
                            <span class="text-[11px] text-slate-400 line-clamp-1">20% discount code voucher</span>
                        </button>

                        <button type="button" 
                                @click="setTemplate('🚚 FREE EXPRESS SHIPPING: Weekend Only', 'Get free express delivery on all orders placed before midnight Sunday. No minimum order required.', '{{ url('/shop') }}')"
                                class="p-2.5 rounded-xl border border-slate-200 hover:border-slate-400 bg-slate-50/50 hover:bg-slate-100 text-left transition-colors cursor-pointer">
                            <span class="block text-xs font-extrabold text-slate-900">🚚 Free Shipping Alert</span>
                            <span class="text-[11px] text-slate-400 line-clamp-1">Zero delivery fee promotional push</span>
                        </button>

                        <button type="button" 
                                @click="setTemplate('⚡ LOW STOCK ALERT: Only a few pieces left!', 'Your favorite oversized fits are running out of stock fast. Secure your size today.', '{{ url('/shop') }}')"
                                class="p-2.5 rounded-xl border border-slate-200 hover:border-slate-400 bg-slate-50/50 hover:bg-slate-100 text-left transition-colors cursor-pointer">
                            <span class="block text-xs font-extrabold text-slate-900">⚡ Low Stock Urgency</span>
                            <span class="text-[11px] text-slate-400 line-clamp-1">Limited inventory countdown notice</span>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Notification Title --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Broadcast Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                                id="title" 
                                name="title" 
                                x-model="title"
                                required 
                                placeholder="e.g. MEGA SALE: Flat 50% Off Everything!"
                                class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all @error('title') border-rose-500 @enderror">
                        @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Message Body --}}
                    <div>
                        <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Message Body <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  x-model="message"
                                  rows="3" 
                                  required 
                                  placeholder="Type the message description that will appear on user screens..."
                                  class="w-full p-3.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('message') border-rose-500 @enderror"></textarea>
                        @error('message') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Call-To-Action Link URL --}}
                    <div>
                        <label for="link" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Target Redirect Link (Optional)
                        </label>
                        <input type="text" 
                                id="link" 
                                name="link" 
                                x-model="link"
                                placeholder="https://threadax.co.in/shop?category=hoodies"
                                class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Users clicking the notification will be taken to this landing page.</p>
                    </div>

                    {{-- Target Audience Segmentation --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Target Customer Audience Segment <span class="text-rose-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label :class="target === 'all_users' ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'" 
                                   class="p-3 rounded-xl border-2 flex flex-col justify-between cursor-pointer transition-all shadow-2xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-extrabold">All Users</span>
                                    <input type="radio" name="target" value="all_users" x-model="target" class="hidden">
                                </div>
                                <span class="text-[11px] opacity-80">{{ $stats['total'] }} recipients</span>
                            </label>

                            <label :class="target === 'buyers' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'" 
                                   class="p-3 rounded-xl border-2 flex flex-col justify-between cursor-pointer transition-all shadow-2xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-extrabold">Active Buyers</span>
                                    <input type="radio" name="target" value="buyers" x-model="target" class="hidden">
                                </div>
                                <span class="text-[11px] opacity-80">{{ $stats['buyers'] }} recipients</span>
                            </label>

                            <label :class="target === 'repeat' ? 'border-purple-600 bg-purple-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'" 
                                   class="p-3 rounded-xl border-2 flex flex-col justify-between cursor-pointer transition-all shadow-2xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-extrabold">VIP Repeat</span>
                                    <input type="radio" name="target" value="repeat" x-model="target" class="hidden">
                                </div>
                                <span class="text-[11px] opacity-80">{{ $stats['repeat'] }} VIPs</span>
                            </label>
                        </div>
                    </div>

                    {{-- Dispatch Button --}}
                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit" 
                                onclick="return confirm('Broadcast this notification to selected customers?');"
                                class="w-full sm:w-auto px-7 py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Dispatch Live Notification Broadcast</span>
                        </button>
                    </div>

                </form>

            </div>

        </div>

        {{-- Right Column: Live Mobile Push Simulator & History (1/3 width) --}}
        <div class="space-y-6">
            
            {{-- Smartphone Push Notification Lockscreen Simulator --}}
            <div class="bg-slate-950 rounded-2xl p-6 text-white shadow-xl border border-slate-800 space-y-4">
                <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider">
                    <span>📱 Lockscreen Simulator</span>
                    <span>Push Preview</span>
                </div>

                {{-- Mock iOS / Android Notification Card --}}
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 space-y-2 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-md bg-black text-white font-extrabold text-[9px] flex items-center justify-center border border-white/20">
                                TX
                            </div>
                            <span class="text-[11px] font-black tracking-wider uppercase text-white">THREADAX</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium">now</span>
                    </div>

                    <div>
                        <h4 class="font-extrabold text-xs text-white line-clamp-1" x-text="title || 'Notification Headline'"></h4>
                        <p class="text-[11px] text-slate-300 line-clamp-3 leading-snug mt-0.5" x-text="message || 'Notification body preview will be displayed here...'"></p>
                    </div>

                    <template x-if="link">
                        <div class="pt-1.5 border-t border-white/10 flex items-center justify-between text-[10px] text-amber-400 font-bold">
                            <span>Tap to open in store</span>
                            <span>➔</span>
                        </div>
                    </template>
                </div>

                <div class="text-[11px] text-slate-400 text-center font-medium">
                    Delivered instantly to user notification trays & account menus.
                </div>
            </div>

            {{-- Recent Broadcast History --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Recent Broadcasts</span>
                </div>

                <div class="space-y-3">
                    @forelse($recentBroadcasts as $broadcast)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 line-clamp-1">{{ $broadcast->payload['title'] ?? 'Broadcast' }}</span>
                                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded-md shrink-0">
                                    {{ $broadcast->recipients_count }} users
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-2">{{ $broadcast->payload['message'] ?? '' }}</p>
                            <span class="text-[9px] text-slate-400 block pt-0.5">{{ \Carbon\Carbon::parse($broadcast->created_at)->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            No broadcasts dispatched yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- Subscribed Push Devices & Phones Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    📱
                </div>
                <div>
                    <h3 class="text-base font-heading font-extrabold text-slate-900">Subscribed Push Devices & Phones</h3>
                    <p class="text-xs text-slate-500">Live list of smartphones and browsers registered to receive web push notifications</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $subscribedDevices->total() }} Devices Registered
                </span>
            </div>
        </div>

        <div class="overflow-x-auto -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4 rounded-l-xl">Device & Phone Model</th>
                        <th class="py-3 px-4">Device Type</th>
                        <th class="py-3 px-4">Browser</th>
                        <th class="py-3 px-4">User / Visitor</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">Subscribed</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($subscribedDevices as $device)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            {{-- Phone / Device Model --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shrink-0 {{ in_array($device->device_type, ['android', 'ios']) ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-600' }}">
                                        @if(str_contains(strtolower($device->device_name), 'iphone') || str_contains(strtolower($device->device_name), 'ipad') || str_contains(strtolower($device->device_name), 'mac'))
                                            🍏
                                        @elseif(in_array($device->device_type, ['android', 'ios']) || str_contains(strtolower($device->device_name), 'phone') || str_contains(strtolower($device->device_name), 'galaxy'))
                                            📱
                                        @else
                                            💻
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs">
                                            {{ $device->device_name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono truncate max-w-[180px]" title="{{ $device->token }}">
                                            Token: {{ substr($device->token, 0, 16) }}...
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Device Type Badge --}}
                            <td class="py-3.5 px-4">
                                @if(in_array($device->device_type, ['android', 'ios']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        📱 Mobile Phone
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        💻 Desktop / PC
                                    </span>
                                @endif
                            </td>

                            {{-- Browser --}}
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md text-[11px]">
                                    {{ $device->browser_name }}
                                </span>
                            </td>

                            {{-- User / Guest --}}
                            <td class="py-3.5 px-4">
                                @if($device->user)
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $device->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $device->user->email }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Guest Visitor
                                    </span>
                                @endif
                            </td>

                            {{-- IP Address --}}
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                                {{ $device->ip_address ?: '—' }}
                            </td>

                            {{-- Subscribed time --}}
                            <td class="py-3.5 px-4 text-[11px]">
                                <span class="text-slate-700 font-medium block">{{ $device->updated_at->diffForHumans() }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $device->created_at->format('d M Y, h:i A') }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Send Test Push to this device --}}
                                    <form action="{{ route('admin.notifications.test-device', $device) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="Send test push notification specifically to this phone/device"
                                                onclick="return confirm('Send direct test push notification to {{ addslashes($device->device_name) }}?');"
                                                class="px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] transition-colors flex items-center gap-1 cursor-pointer">
                                            <span>🚀</span>
                                            <span class="hidden sm:inline">Test Push</span>
                                        </button>
                                    </form>

                                    {{-- Delete Device Token --}}
                                    <form action="{{ route('admin.notifications.device.destroy', $device) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Remove this device token"
                                                onclick="return confirm('Remove this device from notification subscribers?');"
                                                class="p-1.5 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-3xl">📱</span>
                                    <p class="font-bold text-slate-600">No push notification devices subscribed yet</p>
                                    <p class="text-xs text-slate-400 max-w-sm">When visitors or customers tap "Allow" or "Enable VIP Drop Alerts" on the website, their mobile device or browser model will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribedDevices->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $subscribedDevices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
