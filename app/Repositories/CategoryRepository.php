<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        return Category::active()
            ->roots()
            ->with('children')
            ->filter($filters)
            ->ordered()
            ->get();
    }

    public function paginateAdmin(array $filters = [], int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Category::with('parent')
            ->withCount(['products', 'children'])
            ->filter($filters)
            ->ordered()
            ->paginate($perPage);
    }

    public function getFeatured(int $limit = 4): Collection
    {
        return Category::active()
            ->roots()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)
            ->with(['products' => fn ($q) => $q->active()->with('images')])
            ->first();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return (bool) $category->delete();
    }
}
