<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Http\Requests\Invoice\MarkAsPaidRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoicePlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_invoices');

        $query = Invoice::with('clientContract.client', 'invoicePlan');

        // Filter by contract
        if ($request->has('client_contract_id')) {
            $query->forContract($request->client_contract_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filter overdue invoices
        if ($request->boolean('overdue_only')) {
            $query->overdue();
        }

        // Search by invoice number
        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($invoices),
            'pagination' => [
                'current_page' => $invoices->currentPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
                'last_page' => $invoices->lastPage(),
            ],
        ]);
    }

    /**
     * Generate invoice from invoice plan.
     */
    public function generateFromPlan(Request $request)
    {
        $this->authorize('create_invoices');

        $request->validate([
            'invoice_plan_id' => 'required|uuid|exists:invoice_plans,id',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
        ]);

        try {
            DB::beginTransaction();

            $invoicePlan = InvoicePlan::with('clientContract')->findOrFail($request->invoice_plan_id);

            $invoiceDate = $request->invoice_date ?? now();
            $dueDate = $request->due_date ?? now()->addDays(30);

            $invoice = Invoice::create([
                'invoice_plan_id' => $invoicePlan->id,
                'client_contract_id' => $invoicePlan->client_contract_id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'amount' => $invoicePlan->amount_per_invoice,
                'tax_amount' => $invoicePlan->tax_amount,
                'total_amount' => $invoicePlan->total_amount,
                'status' => 'Draft',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully',
                'data' => new InvoiceResource($invoice->load('clientContract.client', 'invoicePlan')),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();

        // Generate invoice number if not provided
        if (!isset($data['invoice_number'])) {
            $data['invoice_number'] = Invoice::generateInvoiceNumber();
        }

        $invoice = Invoice::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully',
            'data' => new InvoiceResource($invoice->load('clientContract.client')),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view_invoices');

        return response()->json([
            'success' => true,
            'data' => new InvoiceResource($invoice->load('clientContract.client', 'invoicePlan', 'attachments')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully',
            'data' => new InvoiceResource($invoice->load('clientContract.client')),
        ]);
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(MarkAsPaidRequest $request, Invoice $invoice)
    {
        if ($invoice->status === 'Paid') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice already marked as paid',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $invoice->markAsPaid($request->payment_method);

            // Create income transaction
            Transaction::create([
                'transaction_date' => now(),
                'transaction_type' => 'Income',
                'category' => 'Invoice Payment',
                'amount' => $invoice->total_amount,
                'reference_type' => Invoice::class,
                'reference_id' => $invoice->id,
                'payment_method' => $request->payment_method,
                'description' => "Payment for invoice {$invoice->invoice_number}",
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice marked as paid successfully',
                'data' => new InvoiceResource($invoice->load('clientContract.client')),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark invoice as paid: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete_invoices');

        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully',
        ]);
    }
}
