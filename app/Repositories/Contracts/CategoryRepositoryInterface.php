<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection;
    public function paginateAdmin(array $filters = [], int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator;
    public function getFeatured(int $limit = 4): \Illuminate\Database\Eloquent\Collection;
    public function findBySlug(string $slug): ?\App\Models\Category;
    public function create(array $data): \App\Models\Category;
    public function update(\App\Models\Category $category, array $data): \App\Models\Category;
    public function delete(\App\Models\Category $category): bool;
}
