<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Faq;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();

        $faqs = null;
        if ($slug === 'faq') {
            $faqs = Faq::active()->ordered()->get();
        }

        // If a specific view exists for this page (e.g., about-us.blade.php), use it.
        if (view()->exists('frontend.pages.static.' . $slug)) {
            return view('frontend.pages.static.' . $slug, compact('page', 'faqs'));
        }

        return view('frontend.pages.static.show', compact('page'));
    }
}
