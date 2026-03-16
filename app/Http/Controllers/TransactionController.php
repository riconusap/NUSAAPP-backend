<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_transactions');

        $query = Transaction::query();

        // Filter by transaction type
        if ($request->has('transaction_type')) {
            $query->byType($request->transaction_type);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by description
        if ($request->has('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => TransactionResource::collection($transactions),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
            ],
        ]);
    }

    /**
     * Get financial summary for a period.
     */
    public function summary(Request $request)
    {
        $this->authorize('view_transactions');

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $totalIncome = Transaction::getTotalIncome($startDate, $endDate);
        $totalExpense = Transaction::getTotalExpense($startDate, $endDate);
        $netIncome = Transaction::getNetIncome($startDate, $endDate);

        // Category breakdown
        $incomeByCategory = Transaction::income()
            ->dateRange($startDate, $endDate)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $expenseByCategory = Transaction::expense()
            ->dateRange($startDate, $endDate)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'summary' => [
                    'total_income' => (float) $totalIncome,
                    'total_expense' => (float) $totalExpense,
                    'net_income' => (float) $netIncome,
                ],
                'income_by_category' => $incomeByCategory->map(function ($item) {
                    return [
                        'category' => $item->category,
                        'total' => (float) $item->total,
                    ];
                }),
                'expense_by_category' => $expenseByCategory->map(function ($item) {
                    return [
                        'category' => $item->category,
                        'total' => (float) $item->total,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaction = Transaction::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view_transactions');

        return response()->json([
            'success' => true,
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete_transactions');

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully',
        ]);
    }
}
