<?php

namespace Tests\App\Http\Controllers;

use App\Models\Expense;
use Tests\TestCase;

class ReportsTest extends TestCase
{

    /** @test */
    public function itWillReportTotalExpenses() : void
    {
        Expense::factory()->count(10)->create();

        $this
            ->getJson(route('reports'))
            ->assertSuccessful()
            ->assertJson([
              'data' => [
                  'total_expenses' => "10"
              ]
            ]);

    }

    /** @test */
    public function itWillReportAverageExpenses() : void
    {
        $expense1 = Expense::factory()->create([
            'amount' => 10
        ]);

        $expense2 = Expense::factory()->create([
            'amount' => 15
        ]);

        $this
            ->getJson(route('reports'))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'average_amount' => ($expense1->amount + $expense2->amount) / 2
                ]
            ]);
    }

    /** @test */
    public function itWillReportTotalAmount() : void
    {
        $expense1 = Expense::factory()->create([
            'amount' => 10
        ]);

        $expense2 = Expense::factory()->create([
            'amount' => 15
        ]);

        $this
            ->getJson(route('reports'))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'total_amount' => $expense1->amount + $expense2->amount
                ]
            ]);
    }
}
