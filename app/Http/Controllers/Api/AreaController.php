<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Area\StoreAreaRequest;
use App\Http\Requests\Area\UpdateAreaRequest;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends ApiController
{
    /**
     * Display a listing of areas.
     */
    public function index(Request $request)
    {
        $query = Area::query();

        // Filter by site
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('area_name', 'like', "%{$search}%");
        }

        // Load relationships
        $query->with(['site.client']);

        $areas = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'areas' => AreaResource::collection($areas),
            'pagination' => [
                'current_page' => $areas->currentPage(),
                'last_page' => $areas->lastPage(),
                'per_page' => $areas->perPage(),
                'total' => $areas->total(),
            ],
        ]);
    }

    /**
     * Store a newly created area.
     */
    public function store(StoreAreaRequest $request)
    {
        try {
            $area = Area::create($request->validated());

            return $this->success(
                new AreaResource($area->load(['site.client'])),
                'Area created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create area: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified area.
     */
    public function show(Area $area)
    {
        return $this->success(
            new AreaResource($area->load(['site.client']))
        );
    }

    /**
     * Update the specified area.
     */
    public function update(UpdateAreaRequest $request, Area $area)
    {
        try {
            $area->update($request->validated());

            return $this->success(
                new AreaResource($area->load(['site.client'])),
                'Area updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update area: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified area.
     */
    public function destroy(Area $area)
    {
        try {
            $area->delete();

            return $this->success(null, 'Area deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete area: ' . $e->getMessage(), 500);
        }
    }
}
