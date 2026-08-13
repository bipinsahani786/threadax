<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class HomeController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepo,
        private ProductRepositoryInterface $productRepo
    ) {}

    /**
     * Show the homepage.
     */
    public function index()
    {
        $featuredCategories = $this->categoryRepo->getFeatured(limit: 4);
        $newArrivals        = $this->productRepo->getNewArrivals(limit: 12);
        $bestSellers        = collect(); // Not implemented yet
        
        $banners = \App\Models\Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('position');

        $faqs = \App\Models\Faq::active()->ordered()->get();

        return view('welcome', compact('featuredCategories', 'newArrivals', 'bestSellers', 'banners', 'faqs'));
    }
}
