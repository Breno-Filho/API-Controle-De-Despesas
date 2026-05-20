<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Auth::user()->transactions()->with('category');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date')) {
            $query->where('date', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        return response()->json($transactions);
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = Auth::user()->transactions()->create($request->validated());
        $transaction->load('category');

        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $transaction->load('category');

        return response()->json($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $transaction->update($request->validated());
        $transaction->load('category');

        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transação removida com sucesso.']);
    }
}
