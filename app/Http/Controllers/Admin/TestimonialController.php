<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $rating = $request->query('rating', 'all');
        $search = $request->query('search');

        $query = Testimonial::orderBy('sort_order')->orderBy('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($rating !== 'all' && is_numeric($rating)) {
            $query->where('rating', (int) $rating);
        }

        $testimonials = $query->paginate(15)->withQueryString();

        $stats = [
            'total'      => Testimonial::count(),
            'active'     => Testimonial::where('is_active', true)->count(),
            'five_star'  => Testimonial::where('rating', 5)->count(),
            'avg_rating' => round(Testimonial::avg('rating') ?? 5.0, 1),
        ];

        return view('admin.pages.testimonials.index', compact('testimonials', 'stats', 'search', 'status', 'rating'));
    }

    public function create()
    {
        return view('admin.pages.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'photo_url'   => 'nullable|url|max:500',
            'video_url'   => 'nullable|url|max:500',
            'content'     => 'required|string|max:1000',
            'rating'      => 'required|integer|min:1|max:5',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial added successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.pages.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'photo_url'   => 'nullable|url|max:500',
            'video_url'   => 'nullable|url|max:500',
            'content'     => 'required|string|max:1000',
            'rating'      => 'required|integer|min:1|max:5',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }
}
