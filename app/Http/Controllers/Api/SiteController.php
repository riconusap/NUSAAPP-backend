<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Site\StoreSiteRequest;
use App\Http\Requests\Site\UpdateSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends ApiController
{
    /**
     * Display a listing of sites.
     */
    public function index(Request $request)
    {
        $query = Site::query();

        // Filter by client
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('site_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Load relationships
        $query->with(['client', 'areas', 'contracts']);

        $sites = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'sites' => SiteResource::collection($sites),
            'pagination' => [
                'current_page' => $sites->currentPage(),
                'last_page' => $sites->lastPage(),
                'per_page' => $sites->perPage(),
                'total' => $sites->total(),
            ],
        ]);
    }

    /**
     * Store a newly created site.
     */
    public function store(StoreSiteRequest $request)
    {
        try {
            $site = Site::create($request->validated());

            return $this->success(
                new SiteResource($site->load(['client', 'areas', 'contracts'])),
                'Site created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create site: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified site.
     */
    public function show(Site $site)
    {
        return $this->success(
            new SiteResource($site->load(['client', 'areas', 'contracts']))
        );
    }

    /**
     * Update the specified site.
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        try {
            $site->update($request->validated());

            return $this->success(
                new SiteResource($site->load(['client', 'areas', 'contracts'])),
                'Site updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update site: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified site.
     */
    public function destroy(Site $site)
    {
        try {
            $site->delete();

            return $this->success(null, 'Site deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete site: ' . $e->getMessage(), 500);
        }
    }
}
