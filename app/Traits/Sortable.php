<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * Apply dynamic sorting to the query.
     * Allowed sort columns defined in $sortableFields on model.
     *
     * @param  Builder  $query
     * @param  string  $column
     * @param  string  $direction
     */
    public function scopeSortBy(Builder $query, string $column = 'created_at', string $direction = 'desc'): Builder
    {
        $allowed = $this->sortableFields ?? ['created_at', 'name', 'price', 'updated_at'];
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'desc';

        if (! in_array($column, $allowed)) {
            $column = 'created_at';
        }

        return $query->orderBy($column, $direction);
    }
}
