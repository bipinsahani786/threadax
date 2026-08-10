<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function all(array $filters = [], string $sortBy = 'created_at', string $direction = 'desc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function paginateAdmin(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function getNewArrivals(int $limit = 8): \Illuminate\Database\Eloquent\Collection;
    public function getBestSellers(int $limit = 4): \Illuminate\Database\Eloquent\Collection;
    public function getFeatured(int $limit = 4): \Illuminate\Database\Eloquent\Collection;
    public function findBySlug(string $slug): ?\App\Models\Product;
    public function create(array $data): \App\Models\Product;
    public function update(\App\Models\Product $product, array $data): \App\Models\Product;
    public function delete(\App\Models\Product $product): bool;
}
