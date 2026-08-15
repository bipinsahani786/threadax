<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic Google-compliant XML Sitemap
     */
    public function index(): Response
    {
        // 1. Static Core Pages
        $staticPages = [
            [
                'url'        => route('frontend.home'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority'   => '1.0',
            ],
            [
                'url'        => route('frontend.products.index'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority'   => '0.9',
            ],
            [
                'url'        => route('frontend.tracking'),
                'lastmod'    => now()->subDays(1)->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.6',
            ],
            [
                'url'        => route('frontend.blog.index'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ],
        ];

        // 2. Dynamic Categories
        $categories = Category::where('is_active', true)
            ->latest('updated_at')
            ->get()
            ->map(function ($cat) {
                return [
                    'url'        => route('frontend.products.index', ['category' => $cat->slug]),
                    'lastmod'    => $cat->updated_at ? $cat->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'image'      => $cat->image ? asset($cat->image) : null,
                    'title'      => $cat->name,
                ];
            });

        // 3. Dynamic Active Products
        $products = Product::where('is_active', true)
            ->with(['images', 'category'])
            ->latest('updated_at')
            ->get()
            ->map(function ($prod) {
                $primaryImg = $prod->images->where('is_primary', true)->first() ?? $prod->images->first();
                return [
                    'url'        => route('frontend.products.show', $prod->slug),
                    'lastmod'    => $prod->updated_at ? $prod->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority'   => '0.8',
                    'image'      => $primaryImg ? asset($primaryImg->image_path) : null,
                    'title'      => $prod->name,
                ];
            });

        // 4. Dynamic Blogs & Articles
        $blogs = Blog::published()
            ->latest('updated_at')
            ->get()
            ->map(function ($blog) {
                return [
                    'url'        => route('frontend.blog.show', $blog->slug),
                    'lastmod'    => $blog->updated_at ? $blog->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                    'image'      => $blog->featured_image_url,
                    'title'      => $blog->title,
                ];
            });

        // 5. Dynamic CMS Static Pages (About, Contact, FAQs, Privacy Policy, Terms, etc.)
        $cmsPages = Page::active()
            ->latest('updated_at')
            ->get()
            ->map(function ($page) {
                return [
                    'url'        => route('frontend.page.show', $page->slug),
                    'lastmod'    => $page->updated_at ? $page->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority'   => '0.5',
                    'title'      => $page->title,
                ];
            });

        $xml = view('frontend.sitemap', compact(
            'staticPages',
            'categories',
            'products',
            'blogs',
            'cmsPages'
        ))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}
