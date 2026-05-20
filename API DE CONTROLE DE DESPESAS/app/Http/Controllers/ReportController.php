<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $query = Auth::user()->transactions();

        if ($request->has('start_date')) {
            $query->where('date', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        $transactions = $query->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return response()->json([
            'total_income' => round($totalIncome, 2),
            'total_expense' => round($totalExpense, 2),
            'balance' => round($balance, 2),
        ]);
    }
}
