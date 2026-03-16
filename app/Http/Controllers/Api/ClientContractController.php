<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ClientContract\StoreClientContractRequest;
use App\Http\Requests\ClientContract\UpdateClientContractRequest;
use App\Http\Resources\ClientContractResource;
use App\Models\ClientContract;
use Illuminate\Http\Request;

class ClientContractController extends ApiController
{
    /**
     * Display a listing of contracts.
     */
    public function index(Request $request)
    {
        $query = ClientContract::query();

        // Filter by client
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by contract type
        if ($request->has('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Filter by active status
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // Load relationships
        $query->with(['client']);

        $contracts = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'contracts' => ClientContractResource::collection($contracts),
            'pagination' => [
                'current_page' => $contracts->currentPage(),
                'last_page' => $contracts->lastPage(),
                'per_page' => $contracts->perPage(),
                'total' => $contracts->total(),
            ],
        ]);
    }

    /**
     * Store a newly created contract.
     */
    public function store(StoreClientContractRequest $request)
    {
        try {
            $contract = ClientContract::create($request->validated());

            return $this->success(
                new ClientContractResource($contract->load(['client'])),
                'Contract created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create contract: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified contract.
     */
    public function show(ClientContract $clientContract)
    {
        return $this->success(
            new ClientContractResource($clientContract->load(['client']))
        );
    }

    /**
     * Update the specified contract.
     */
    public function update(UpdateClientContractRequest $request, ClientContract $clientContract)
    {
        try {
            $clientContract->update($request->validated());

            return $this->success(
                new ClientContractResource($clientContract->load(['client'])),
                'Contract updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update contract: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified contract.
     */
    public function destroy(ClientContract $clientContract)
    {
        try {
            $clientContract->delete();

            return $this->success(null, 'Contract deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete contract: ' . $e->getMessage(), 500);
        }
    }
}
