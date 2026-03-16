<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SiteInventory\StoreSiteInventoryRequest;
use App\Http\Requests\SiteInventory\UpdateSiteInventoryRequest;
use App\Http\Resources\SiteInventoryResource;
use App\Models\SiteInventory;
use Illuminate\Http\Request;

class SiteInventoryController extends ApiController
{
    /**
     * Display a listing of site inventories.
     */
    public function index(Request $request)
    {
        $query = SiteInventory::query();

        // Filter by site
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by inventory item
        if ($request->has('inventory_item_id')) {
            $query->where('inventory_item_id', $request->inventory_item_id);
        }

        // Filter low stock
        if ($request->has('low_stock') && $request->low_stock) {
            $query->lowStock();
        }

        // Load relationships
        $query->with(['site', 'inventoryItem']);

        $inventories = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'inventories' => SiteInventoryResource::collection($inventories),
            'pagination' => [
                'current_page' => $inventories->currentPage(),
                'last_page' => $inventories->lastPage(),
                'per_page' => $inventories->perPage(),
                'total' => $inventories->total(),
            ],
        ]);
    }

    /**
     * Store a newly created site inventory.
     */
    public function store(StoreSiteInventoryRequest $request)
    {
        try {
            $inventory = SiteInventory::create($request->validated());

            return $this->success(
                new SiteInventoryResource($inventory->load(['site', 'inventoryItem'])),
                'Site inventory created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create site inventory: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified site inventory.
     */
    public function show(SiteInventory $siteInventory)
    {
        return $this->success(
            new SiteInventoryResource($siteInventory->load(['site', 'inventoryItem']))
        );
    }

    /**
     * Update the specified site inventory.
     */
    public function update(UpdateSiteInventoryRequest $request, SiteInventory $siteInventory)
    {
        try {
            $siteInventory->update($request->validated());

            return $this->success(
                new SiteInventoryResource($siteInventory->load(['site', 'inventoryItem'])),
                'Site inventory updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update site inventory: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified site inventory.
     */
    public function destroy(SiteInventory $siteInventory)
    {
        try {
            $siteInventory->delete();

            return $this->success(null, 'Site inventory deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete site inventory: ' . $e->getMessage(), 500);
        }
    }
}
