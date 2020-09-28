<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Expense;

/**
 * Class ExpenseResource
 * @package App\Http\Resources
 * @property $resource Expense
 */
class ExpenseResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(
            parent::toArray($request),
            [
                'type' => $this->resource->amount > 0 ? 'invoice' : 'expense',
            ]
        );
    }
}
