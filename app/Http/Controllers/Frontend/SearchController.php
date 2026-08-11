<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private ProductRepositoryInterface $productRepo) {}

    public function index(Request $request)
    {
        $query    = $request->get('q', $request->get('search', ''));
        $products = collect();

        if (strlen(trim($query)) >= 2) {
            $products = $this->productRepo->all(
                ['search' => $query, 'status' => '1'],
                'created_at',
                'desc'
            );
        }

        return view('frontend.pages.search.index', compact('products', 'query'));
    }
}
