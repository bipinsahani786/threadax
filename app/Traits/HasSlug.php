<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the HasSlug trait.
     */
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->getOriginal('slug') === $model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });
    }

    /**
     * Generate a unique slug from the given value.
     */
    protected function generateUniqueSlug(string $value): string
    {
        $slug = Str::slug($value);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key name for model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
