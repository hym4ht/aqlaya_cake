<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'name',
        'slug',
        'image_url',
        'price',
        'quantity',
        'size',
        'custom_message',
        'scheduled_date',
        'scheduled_time',
        'notes',
        'line_total',
    ];

    protected $casts = [
        'price'     => 'float',
        'quantity'  => 'integer',
        'line_total' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
