<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = Faq::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $faqs = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => Faq::count(),
            'active'   => Faq::where('is_active', true)->count(),
            'inactive' => Faq::where('is_active', false)->count(),
        ];

        return view('admin.pages.faqs.index', compact('faqs', 'stats', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.pages.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ question created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.pages.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ question updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
