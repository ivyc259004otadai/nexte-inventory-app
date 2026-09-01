<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'store_id',
        'current_stock',
        'required_stock',
        'desired_order_quantity',
        'weekly_sales',
        'last_checked_at',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'current_stock' => 'integer',
            'required_stock' => 'integer',
            'desired_order_quantity' => 'integer',
            'weekly_sales' => 'integer',
            'last_checked_at' => 'date',
        ];
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
