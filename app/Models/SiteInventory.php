<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'inventory_item_id',
        'stock_quantity',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the site that owns the inventory.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the inventory item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Check if stock is low (less than 10).
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity < 10;
    }

    /**
     * Scope a query to only include low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '<', 10);
    }
}
