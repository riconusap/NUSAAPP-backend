<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicePlan\StoreInvoicePlanRequest;
use App\Http\Requests\InvoicePlan\UpdateInvoicePlanRequest;
use App\Http\Resources\InvoicePlanResource;
use App\Models\InvoicePlan;
use Illuminate\Http\Request;

class InvoicePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_invoices');

        $query = InvoicePlan::with('clientContract.client', 'invoices');

        // Filter by contract
        if ($request->has('client_contract_id')) {
            $query->forContract($request->client_contract_id);
        }

        // Filter by invoice schedule
        if ($request->has('invoice_schedule')) {
            $query->where('invoice_schedule', $request->invoice_schedule);
        }

        $invoicePlans = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => InvoicePlanResource::collection($invoicePlans),
            'pagination' => [
                'current_page' => $invoicePlans->currentPage(),
                'per_page' => $invoicePlans->perPage(),
                'total' => $invoicePlans->total(),
                'last_page' => $invoicePlans->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoicePlanRequest $request)
    {
        $invoicePlan = InvoicePlan::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Invoice plan created successfully',
            'data' => new InvoicePlanResource($invoicePlan->load('clientContract.client')),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicePlan $invoicePlan)
    {
        $this->authorize('view_invoices');

        return response()->json([
            'success' => true,
            'data' => new InvoicePlanResource($invoicePlan->load('clientContract.client', 'invoices')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoicePlanRequest $request, InvoicePlan $invoicePlan)
    {
        $invoicePlan->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Invoice plan updated successfully',
            'data' => new InvoicePlanResource($invoicePlan->load('clientContract.client')),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicePlan $invoicePlan)
    {
        $this->authorize('delete_invoices');

        $invoicePlan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice plan deleted successfully',
        ]);
    }
}
