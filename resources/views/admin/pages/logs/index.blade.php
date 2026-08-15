@extends('admin.layouts.app')

@section('title', 'System Logs & Diagnostics')
@section('page-title', 'Server Diagnostics & Maintenance')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ expandedLogs: {}, activeStack: null, showStackModal: false }">

    {{-- Top Action Header & Optimizer Bar --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xl">🖥️</span>
                <h1 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight">System Logs & Optimizer</h1>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                    v{{ $systemInfo['laravel_version'] }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-400">Real-time application exceptions, runtime errors, and cache maintenance controls.</p>
        </div>

        {{-- Quick Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Optimize Clear --}}
            <form action="{{ route('admin.logs.optimize') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="optimize_clear">
                <button type="submit" onclick="return confirm('Clear all application caches (config, views, routes, and compiled classes)?');" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-xs active:scale-95 cursor-pointer">
                    <span>⚡ Clear All Cache</span>
                </button>
            </form>

            {{-- Optimize Cache --}}
            <form action="{{ route('admin.logs.optimize') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="optimize_cache">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs active:scale-95 cursor-pointer">
                    <span>🚀 Optimize & Cache</span>
                </button>
            </form>

            {{-- Download Log --}}
            <a href="{{ route('admin.logs.download', ['file' => $currentFile]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                <span>📥 Download Log</span>
            </a>

            {{-- Clear Log File --}}
            <form action="{{ route('admin.logs.clear') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="file" value="{{ $currentFile }}">
                <button type="submit" onclick="return confirm('Are you sure you want to empty the {{ $currentFile }} file? All existing logs will be wiped.');" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-all cursor-pointer">
                    <span>🗑️ Empty Log</span>
                </button>
            </form>
        </div>
    </div>

    {{-- System Health Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Log Size & Entries --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Current Log File</p>
                    <h3 class="text-xl font-heading font-black text-slate-900 mt-1 truncate max-w-[170px]" title="{{ $currentFile }}">{{ $currentFile }}</h3>
                    <p class="text-[11px] text-slate-500 font-mono mt-1">Size: <strong>{{ $fileSizeFormatted }}</strong> ({{ number_format($counts['total']) }} lines)</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                    📁
                </div>
            </div>
        </div>

        {{-- Errors & Exceptions --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Errors & Exceptions</p>
                    <h3 class="text-2xl font-heading font-black text-rose-600 mt-1">{{ number_format($counts['error']) }}</h3>
                    <p class="text-[11px] text-rose-500 font-bold mt-1">Critical & Runtime issues</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-xs">
                    🔴
                </div>
            </div>
        </div>

        {{-- Warnings --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Warnings</p>
                    <h3 class="text-2xl font-heading font-black text-amber-600 mt-1">{{ number_format($counts['warning']) }}</h3>
                    <p class="text-[11px] text-amber-600 font-bold mt-1">Non-breaking alerts</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                    🟡
                </div>
            </div>
        </div>

        {{-- Info & Notices --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Info & Events</p>
                    <h3 class="text-2xl font-heading font-black text-slate-900 mt-1">{{ number_format($counts['info']) }}</h3>
                    <p class="text-[11px] text-blue-600 font-bold mt-1">Lifecycle events & webhooks</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl shadow-xs">
                    ℹ️
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search & File Selector Toolbar --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            {{-- Log File Selector --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Select Log File</label>
                <select name="file" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-900">
                    @foreach($files as $f)
                        <option value="{{ $f['name'] }}" {{ $currentFile === $f['name'] ? 'selected' : '' }}>
                            {{ $f['name'] }} ({{ $f['size'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Level Filter --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Log Level</label>
                <select name="level" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-900">
                    <option value="">All Levels ({{ $counts['total'] }})</option>
                    <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>🔴 Error ({{ $counts['error'] }})</option>
                    <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>🟡 Warning ({{ $counts['warning'] }})</option>
                    <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>🔵 Info ({{ $counts['info'] }})</option>
                    <option value="debug" {{ request('level') === 'debug' ? 'selected' : '' }}>⚪ Debug ({{ $counts['debug'] }})</option>
                </select>
            </div>

            {{-- Search Keyword --}}
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Search in Logs</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search error message, stack trace, class..." 
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-900 focus:bg-white transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                    Filter Logs
                </button>
                @if(request()->hasAny(['search', 'level']))
                    <a href="{{ route('admin.logs.index', ['file' => $currentFile]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Log Entries Feed --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50/75 border-b border-slate-200/80 flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">
                Log Feed (Showing {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }})
            </span>
            <span class="text-[11px] font-mono text-slate-400">
                Newest entries on top ⬇️
            </span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($paginator as $entry)
                @php
                    $isError = in_array(strtolower($entry['level']), ['error', 'critical', 'alert', 'emergency']);
                    $isWarning = strtolower($entry['level']) === 'warning';
                    $isInfo = in_array(strtolower($entry['level']), ['info', 'notice']);
                @endphp
                <div class="p-4 sm:p-5 hover:bg-slate-50/60 transition-colors space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Level Badge --}}
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border
                                @if($isError) bg-rose-50 text-rose-700 border-rose-200
                                @elseif($isWarning) bg-amber-50 text-amber-700 border-amber-200
                                @elseif($isInfo) bg-blue-50 text-blue-700 border-blue-200
                                @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                <span>{{ $isError ? '🔴' : ($isWarning ? '🟡' : ($isInfo ? '🔵' : '⚪')) }}</span>
                                <span>{{ $entry['level'] }}</span>
                            </span>

                            {{-- Environment Badge --}}
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-[10px] font-bold uppercase">
                                {{ $entry['env'] }}
                            </span>

                            {{-- Timestamp --}}
                            <span class="font-mono text-xs font-bold text-slate-500">
                                {{ $entry['timestamp'] }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-1.5">
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('{{ addslashes($entry['message'] . "\n" . $entry['stack']) }}'); if(window.showToast) window.showToast('Log copied to clipboard', 'info');" 
                                    class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-[11px] transition-colors cursor-pointer">
                                📋 Copy
                            </button>
                            @if(!empty($entry['stack']))
                                <button type="button" 
                                        @click="activeStack = {{ json_encode($entry) }}; showStackModal = true;" 
                                        class="px-2.5 py-1 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-[11px] transition-colors cursor-pointer">
                                    🔍 Stack Trace
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Main Log Message --}}
                    <div class="font-mono text-xs text-slate-900 font-bold bg-slate-50 p-3 rounded-xl border border-slate-200/80 select-all overflow-x-auto">
                        {{ $entry['message'] }}
                    </div>

                    {{-- Inline Collapsible Stack Preview if available --}}
                    @if(!empty($entry['stack']))
                        <div x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="text-[11px] font-bold text-blue-600 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <span x-text="open ? '▲ Hide Stack Preview' : '▼ View Inline Stack Preview'"></span>
                            </button>
                            <div x-show="open" x-cloak class="mt-2">
                                <pre class="bg-slate-900 text-slate-300 p-3.5 rounded-xl font-mono text-[11px] overflow-x-auto max-h-52 leading-relaxed select-all">{{ $entry['stack'] }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 px-4">
                    <div class="text-4xl mb-3">✨</div>
                    <h3 class="font-heading font-extrabold text-base text-slate-800">No Log Entries Found</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Either the log file is clean or no entries matched your search query.</p>
                </div>
            @endforelse
        </div>

        @if($paginator->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>

    {{-- System Environment Specs Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
            <span class="text-lg">⚙️</span>
            <h3 class="text-sm font-heading font-extrabold text-slate-900">Runtime & Environment Specifications</h3>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">PHP Version</span>
                <span class="font-mono font-bold text-slate-900">{{ $systemInfo['php_version'] }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Environment</span>
                <span class="font-mono font-bold text-blue-600 uppercase">{{ $systemInfo['environment'] }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Debug Mode</span>
                <span class="font-mono font-bold text-slate-900">{{ $systemInfo['debug_mode'] }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Cache Driver</span>
                <span class="font-mono font-bold text-slate-900">{{ $systemInfo['cache_driver'] }}</span>
            </div>
        </div>
    </div>

    {{-- Stack Trace Detail Modal --}}
    <div x-show="showStackModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="showStackModal = false">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-4xl w-full p-6 shadow-2xl space-y-4" @click.outside="showStackModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔍</span>
                    <div>
                        <h3 class="text-base font-heading font-black text-slate-900">Exception & Stack Trace Inspector</h3>
                        <p class="text-xs text-slate-400" x-text="activeStack?.timestamp"></p>
                    </div>
                </div>
                <button @click="showStackModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-900 font-mono font-bold select-all" x-text="activeStack?.message"></div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Full Trace Output</label>
                    <pre class="bg-slate-900 text-emerald-400 p-4 rounded-2xl font-mono text-[11px] overflow-x-auto max-h-96 leading-relaxed select-all" x-text="activeStack?.stack || 'No trace recorded.'"></pre>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between">
                <button type="button" 
                        onclick="navigator.clipboard.writeText(document.querySelector('.bg-slate-900').innerText); if(window.showToast) window.showToast('Copied full trace', 'info');" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold cursor-pointer">
                    📋 Copy Trace
                </button>
                <button type="button" @click="showStackModal = false" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs cursor-pointer">
                    Close Inspector
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
