@extends('admin.layouts.app')

@section('title', 'Add FAQ')
@section('page-title', 'Create New FAQ')

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.faqs.index') }}" class="text-gray-500 hover:text-gray-900">← Back</a>
    <h1 class="text-2xl font-bold text-gray-900">Create FAQ</h1>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.faqs.store') }}" class="max-w-3xl space-y-6">
    @csrf

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Question *</label>
            <input type="text" name="question" value="{{ old('question') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Answer *</label>
            <textarea name="answer" rows="5" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">{{ old('answer') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
            </div>
            <div class="flex items-center gap-3 pt-6">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-slate-900 focus:ring-slate-400">
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-slate-900 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">Create FAQ</button>
        <a href="{{ route('admin.faqs.index') }}" class="text-sm font-semibold px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</a>
    </div>
</form>
@endsection
