<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepo,
        private CategoryRepositoryInterface $categoryRepo
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'is_active', 'min_price', 'max_price']);
        if ($request->has('sizes')) $filters['sizes'] = $request->get('sizes');
        if ($request->has('colors')) $filters['colors'] = $request->get('colors');
        
        // Make sure we only show active products on frontend
        $filters['is_active'] = 1;

        $sort = $request->get('sort', 'newest');
        
        $sortBy = 'created_at';
        $direction = 'desc';

        if ($sort === 'price_low') {
            $sortBy = 'price';
            $direction = 'asc';
        } elseif ($sort === 'price_high') {
            $sortBy = 'price';
            $direction = 'desc';
        }

        $products = $this->productRepo->all($filters, $sortBy, $direction);
        
        if ($request->ajax()) {
            return view('frontend.pages.products.partials.grid', compact('products'))->render();
        }

        $categories = $this->categoryRepo->all(['is_active' => 1]);
        
        // Filter options
        $maxPrice = \App\Models\Product::max('price') ?? 10000;
        $availableSizes = \App\Models\ProductVariant::whereNotNull('size')->where('size', '!=', '')->distinct()->pluck('size');
        $availableColors = \App\Models\ProductVariant::whereNotNull('color')->where('color', '!=', '')->distinct()->pluck('color');

        return view('frontend.pages.products.index', compact('products', 'categories', 'maxPrice', 'availableSizes', 'availableColors'));
    }

    public function show(string $slug)
    {
        $product = $this->productRepo->findBySlug($slug);

        if (! $product || ! $product->is_active) {
            abort(404);
        }

        // Eager-load approved reviews with the reviewer's name to avoid N+1
        $product->load([
            'approvedReviews' => fn($q) => $q->with('user:id,name')->latest(),
            'images',
            'variants',
            'category',
        ]);

        // Related products from same category
        $relatedProducts = $this->productRepo->all([
            'category' => $product->category_id,
            'status'   => '1',
        ])->where('id', '!=', $product->id)->take(4);

        return view('frontend.pages.products.show', compact('product', 'relatedProducts'));
    }
}
