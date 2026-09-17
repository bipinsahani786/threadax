<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(array $filters = [], string $sortBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator
    {
        return Product::active()
            ->with(['categories', 'images', 'variants'])
            ->filter($filters)
            ->sortBy($sortBy, $direction)
            ->paginate(16);
    }

    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('categories')
            ->filter($filters)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getNewArrivals(int $limit = 8): Collection
    {
        return Product::active()
            ->with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getBestSellers(int $limit = 4): Collection
    {
        // Will be ordered by actual sales data in Phase 4
        // For now, return featured products as "best sellers"
        return Product::active()
            ->featured()
            ->with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getFeatured(int $limit = 4): Collection
    {
        return Product::active()
            ->featured()
            ->with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->with(['categories', 'variants', 'images'])
            ->first();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }
}
