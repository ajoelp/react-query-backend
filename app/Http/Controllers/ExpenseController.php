<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $term = $request->input('search');

        $expenses = Expense::query()
            ->when($term, function (Builder $query) use ($term) {
                $query->where('name', 'LIKE', '%'.$term.'%');
            })
            ->paginate();
        return ExpenseResource::collection($expenses)->toResponse($request);
    }

    public function store(CreateExpenseRequest $request): JsonResponse
    {
        $expense = Expense::query()->create($request->validated());
        return ExpenseResource::make($expense)->toResponse($request);
    }

    public function show(Request $request, Expense $expense): JsonResponse
    {
        return ExpenseResource::make($expense)->toResponse($request);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense->update($request->validated());
        return ExpenseResource::make($expense)->toResponse($request);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
