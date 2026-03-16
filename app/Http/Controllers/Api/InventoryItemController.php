<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\InventoryItem\StoreInventoryItemRequest;
use App\Http\Requests\InventoryItem\UpdateInventoryItemRequest;
use App\Http\Resources\InventoryItemResource;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends ApiController
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        // Filter by category
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter consumable only
        if ($request->has('consumable_only') && $request->consumable_only) {
            $query->consumable();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('item_name', 'like', "%{$search}%");
        }

        // Load relationships
        $query->with(['siteInventories.site']);

        $items = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'items' => InventoryItemResource::collection($items),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(StoreInventoryItemRequest $request)
    {
        try {
            $item = InventoryItem::create($request->validated());

            return $this->success(
                new InventoryItemResource($item),
                'Inventory item created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create inventory item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $inventoryItem)
    {
        return $this->success(
            new InventoryItemResource($inventoryItem->load(['siteInventories.site']))
        );
    }

    /**
     * Update the specified inventory item.
     */
    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventoryItem)
    {
        try {
            $inventoryItem->update($request->validated());

            return $this->success(
                new InventoryItemResource($inventoryItem),
                'Inventory item updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update inventory item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        try {
            $inventoryItem->delete();

            return $this->success(null, 'Inventory item deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete inventory item: ' . $e->getMessage(), 500);
        }
    }
}
