<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $category = $request->query('category', 'all');

        $query = Blog::latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all' && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        if ($category !== 'all' && !empty($category)) {
            $query->where('category', $category);
        }

        $blogs = $query->paginate(12)->withQueryString();

        $stats = [
            'total'       => Blog::count(),
            'published'   => Blog::where('status', 'published')->count(),
            'draft'       => Blog::where('status', 'draft')->count(),
            'total_views' => (int) Blog::sum('views'),
        ];

        $categories = Blog::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.pages.blogs.index', compact('blogs', 'stats', 'search', 'status', 'category', 'categories'));
    }

    public function create()
    {
        return view('admin.pages.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'body'             => 'required|string',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'author'           => 'nullable|string|max:100',
            'status'           => 'required|in:draft,published',
            'featured_image'   => 'nullable|image|max:4096',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;
        $validated['author'] = $validated['author'] ?: 'ThreadAX Editorial';

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article published successfully!');
    }

    public function edit(Blog $blog)
    {
        return view('admin.pages.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'body'             => 'required|string',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'author'           => 'nullable|string|max:100',
            'status'           => 'required|in:draft,published',
            'featured_image'   => 'nullable|image|max:4096',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;
        $validated['author'] = $validated['author'] ?: 'ThreadAX Editorial';

        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog article deleted.');
    }
}
