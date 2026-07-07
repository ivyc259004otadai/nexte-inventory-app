<?php

namespace App\Models;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_code',
        'store_name',
        'address',
        'phone_number',
        'status',
    ];


    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function storeProducts(): HasMany
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'store_products')
            ->withPivot(['id', 'status'])
            ->withTimestamps();
    }
}
