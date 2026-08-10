<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply filters to the query.
     * Each model can define $filterableFields to restrict which fields are allowed.
     *
     * @param  Builder  $query
     * @param  array  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $allowed = $this->filterableFields ?? [];

        foreach ($filters as $key => $value) {
            if (! in_array($key, $allowed) || blank($value)) {
                continue;
            }

            match ($key) {
                'search'     => $query->where(function ($q) use ($value) {
                    $q->where('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }),
                'category'   => $query->where('category_id', $value),
                'min_price'  => $query->where('price', '>=', $value),
                'max_price'  => $query->where('price', '<=', $value),
                'is_active'  => $query->where('is_active', (bool) $value),
                'is_featured' => $query->where('is_featured', (bool) $value),
                default      => $query->where($key, $value),
            };
        }

        return $query;
    }
}
