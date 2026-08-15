@extends('admin.layouts.app')

@section('title', 'Add FAQ - Admin')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.faqs.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <span class="text-lg font-extrabold text-slate-900">Add FAQ Question</span>
            <p class="text-xs text-slate-400">Add common customer inquiries regarding shipping, sizing, and returns</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8"
     x-data="{
         question: '{{ addslashes(old('question', '')) }}',
         answer: `{{ addslashes(old('answer', '')) }}`,
         isActive: {{ old('is_active', true) ? 'true' : 'false' }}
     }">

    {{-- Form --}}
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            ❓
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Question & Response</h3>
                            <p class="text-xs text-slate-400">Customer inquiry and official response</p>
                        </div>
                    </div>

                    {{-- Question --}}
                    <div>
                        <label for="question" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Question Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="question" 
                               name="question" 
                               x-model="question"
                               value="{{ old('question') }}" 
                               required
                               placeholder="e.g. What is ThreadAX's return & exchange policy?"
                               class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-sm font-bold text-slate-900 outline-none transition-all @error('question') border-rose-500 @enderror">
                        @error('question') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Answer --}}
                    <div>
                        <label for="answer" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Answer Content <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="answer" 
                                  name="answer" 
                                  x-model="answer"
                                  rows="7"
                                  required
                                  placeholder="Provide the detailed explanation or instructions..."
                                  class="w-full p-4 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('answer') border-rose-500 @enderror">{{ old('answer') }}</textarea>
                        @error('answer') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Live Accordion Simulator Preview --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4" x-data="{ expanded: true }">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Live Storefront Accordion Preview</span>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                        <button type="button" @click="expanded = !expanded" class="w-full p-4 text-left flex items-center justify-between gap-3 font-extrabold text-xs text-slate-900 cursor-pointer">
                            <span x-text="question || 'Your question title will appear here...'"></span>
                            <span class="text-slate-400 text-sm font-bold" x-text="expanded ? '−' : '+'"></span>
                        </button>
                        <div x-show="expanded" class="p-4 pt-0 text-xs text-slate-600 leading-relaxed border-t border-slate-200/50">
                            <span x-text="answer || 'Answer details and explanations will be shown when customers expand the FAQ.'"></span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Visibility & Sort Order --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Display Settings</h3>
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Sort Priority Order
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', 0) }}" 
                               min="0"
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first (0, 1, 2...).</p>
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Visible on FAQ page</span>
                        </div>
                        <button type="button" 
                                @click="isActive = !isActive" 
                                :class="isActive ? 'bg-slate-900' : 'bg-slate-200'" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-2xs">
                            <span :class="isActive ? 'translate-x-5' : 'translate-x-0'" 
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="is_active" :value="isActive ? '1' : '0'">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Save FAQ Question</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.faqs.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
