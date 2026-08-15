<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = Page::latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $pages = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => Page::count(),
            'active'   => Page::where('is_active', true)->count(),
            'inactive' => Page::where('is_active', false)->count(),
        ];

        return view('admin.pages.pages.index', compact('pages', 'stats', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.pages.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug',
            'body'             => 'required|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'nullable',
        ]);

        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);

        Page::create($validated);
        return redirect()->route('admin.pages.index')->with('success', 'Page ' . $validated['title'] . ' created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'body'             => 'required|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $request->boolean('is_active', false);

        $page->update($validated);
        return redirect()->route('admin.pages.index')->with('success', 'Page ' . $page->title . ' updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully!');
    }
}
