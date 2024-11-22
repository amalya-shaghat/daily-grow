<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Fetch all transactions with optional filtering and sorting.
     */
    public function index(): Collection|array
    {
        return Transaction::query()->with(['users'])->orderBy('operation_date', 'desc')->get();
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'operation_date' => 'required|date',
            'counterparty' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string|in:fact',
            'accounting_article' => 'nullable|string',
            'deal' => 'nullable|string',
        ]);

        $transaction = Transaction::query()->create($validated);
        $transaction->users()->attach(auth()->id());

        return response()->json([
            'message' => 'Transaction created successfully.',
            'transaction' => $transaction,
        ], 201);
    }

    /**
     * Delete a transaction.
     */
    public function destroy($id): JsonResponse
    {

        $transaction = Transaction::query()->findOrFail($id);
        if (!$transaction->users->contains(auth()->id())) {
            return response()
                ->json(['message' => 'Вы не можете удалить эту операцию, так как вы её не создавали.'], 403);
        }
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully.']);
    }

    /**
     * Copy a transaction.
     */
    public function copy($id): JsonResponse
    {
        $transaction = Transaction::query()->findOrFail($id);
        $newTransaction = $transaction->replicate();
        $newTransaction->save();

        $newTransaction->users()->attach(auth()->id());
        return response()->json([
            'message' => 'Transaction copied successfully.',
            'transaction' => $newTransaction,
        ]);
    }
}
