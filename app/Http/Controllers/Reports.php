<?php


namespace App\Http\Controllers;


use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Reports
{
    public function __invoke(Request $request)
    {
        $expenses = Expense::query()
            ->selectRaw("SUM(amount) as total_amount, COUNT(id) as total_expenses, AVG(amount) as average_amount")
            ->first();

        return new JsonResponse([
            'data' => $expenses->toArray(),
        ]);
    }
}
