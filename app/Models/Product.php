<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'excerpt',
        'description',
        'price',
        'stock',
        'is_active',
        'product_type',
        'lead_time_days',
        'sizes',
        'image_url',
        'image_path',
        'image_path_2',
        'image_path_3',
        'is_best_seller',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'sizes' => 'array',
            'lead_time_days' => 'integer',
        ];
    }

    public function isReadyStock(): bool
    {
        return $this->product_type === 'ready_stock';
    }

    public function isPreOrder(): bool
    {
        return $this->product_type === 'pre_order';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }
}
