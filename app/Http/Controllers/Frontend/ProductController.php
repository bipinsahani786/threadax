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
        $filters = $request->only(['search', 'category', 'status', 'min_price', 'max_price']);
        
        // Make sure we only show active products on frontend
        $filters['status'] = '1';

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
        $categories = $this->categoryRepo->all(['status' => '1']);

        return view('frontend.pages.products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = $this->productRepo->findBySlug($slug);

        if (!$product || !$product->is_active) {
            abort(404);
        }

        // Get related products from the same category
        $relatedProducts = $this->productRepo->all([
            'category' => $product->category_id,
            'status' => '1'
        ])->where('id', '!=', $product->id)->take(4);

        return view('frontend.pages.products.show', compact('product', 'relatedProducts'));
    }
}
