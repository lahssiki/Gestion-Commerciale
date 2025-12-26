<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
     protected $fillable = [
        'product_id', 'user_id', 'type', 'quantity', 'reason'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($movement) {
            $product = $movement->product;
            if ($movement->type === 'in') {
                $product->increment('stock', $movement->quantity);
            } else {
                $product->decrement('stock', $movement->quantity);
            }
        });
    }
}
