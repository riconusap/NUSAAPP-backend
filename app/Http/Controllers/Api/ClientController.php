<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends ApiController
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_phone', 'like', "%{$search}%");
            });
        }

        // Load relationships
        $query->with(['sites', 'contracts']);

        $clients = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'clients' => ClientResource::collection($clients),
            'pagination' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
            ],
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $client = Client::create($request->validated());

            return $this->success(
                new ClientResource($client->load(['sites', 'contracts'])),
                'Client created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create client: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        return $this->success(
            new ClientResource($client->load(['sites', 'contracts']))
        );
    }

    /**
     * Update the specified client.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $client->update($request->validated());

            return $this->success(
                new ClientResource($client->load(['sites', 'contracts'])),
                'Client updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update client: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();

            return $this->success(null, 'Client deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete client: ' . $e->getMessage(), 500);
        }
    }
}
