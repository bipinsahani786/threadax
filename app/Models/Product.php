<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\HasSlug;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasSlug, Filterable, Sortable, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'sku',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'video_url',
        'model_3d_url',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active'     => 'boolean',
        'is_featured'   => 'boolean',
    ];

    /** Fields allowed for filtering */
    protected array $filterableFields = [
        'category', 'min_price', 'max_price', 'is_active', 'is_featured', 'search', 'sizes', 'colors', 'in_stock'
    ];

    /** Fields allowed for sorting */
    protected array $sortableFields = [
        'created_at', 'name', 'price', 'updated_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $rawSlug = $product->slug ?: ($product->name ?: 'PRD');
                $slugClean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawSlug));
                $shortCode = substr($slugClean, 0, 8) ?: 'PRD';
                $candidate = 'TX-' . $shortCode;

                $counter = 1;
                $finalSku = $candidate;
                while (static::where('sku', $finalSku)->exists()) {
                    $finalSku = $candidate . '-' . $counter++;
                }
                $product->sku = $finalSku;
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany([
            'is_primary' => 'max',
            'sort_order' => 'min',
            'id' => 'min',
        ]);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // ─── Accessors ────────────────────────────────────────────

    /**
     * Get the effective master SKU.
     */
    public function getEffectiveSkuAttribute(): string
    {
        if (!empty($this->sku)) {
            return $this->sku;
        }

        $rawSlug = $this->slug ?: ($this->name ?: 'PRD');
        $slugClean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawSlug));
        $shortCode = substr($slugClean, 0, 8) ?: 'PRD';

        return 'TX-' . $shortCode . ($this->id ? '-' . $this->id : '');
    }

    /**
     * Get the primary product image.
     */
    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->where('is_primary', true)->first()
            ?? $this->images->first();
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
