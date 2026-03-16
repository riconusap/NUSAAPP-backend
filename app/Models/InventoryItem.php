<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_name',
        'category',
        'unit',
        'is_consumable',
    ];

    protected $casts = [
        'is_consumable' => 'boolean',
    ];

    /**
     * Get the site inventories for this item.
     */
    public function siteInventories(): HasMany
    {
        return $this->hasMany(SiteInventory::class);
    }

    /**
     * Get total stock across all sites.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->siteInventories()->sum('stock_quantity');
    }

    /**
     * Scope a query to only include items by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include consumable items.
     */
    public function scopeConsumable($query)
    {
        return $query->where('is_consumable', true);
    }
}
