<?php

namespace Tests\App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{

    /** @test */
    public function itWillListAllExpenses() : void
    {
        $expenses = Expense::factory()->count(10)->create();

        $this
            ->getJson(route('expenses.index'))
            ->assertSuccessful()
            ->assertJson([
                'data' => $expenses->only(['id', 'name'])->all()
            ]);
    }

    /** @test */
    public function itWillSearchExpenses() : void
    {
        Expense::factory()->count(10)->create([
            'name' => 'random-name'
        ]);

        $term = 'search';
        $searchResult = Expense::factory()->create([
            'name' => $term
        ]);

        $this
            ->getJson(route('expenses.index', ['search' => $term ]))
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $searchResult->id]
                ]
            ]);
    }

    /** @test */
    public function itWillShowAExpense() : void
    {
        $expense = Expense::factory()->create([
            'amount' => -100
        ]);

        $this
            ->getJson(route('expenses.show', [$expense]))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'id' => $expense->id,
                    'type' => 'expense'
                ]
            ]);
    }


    /** @test */
    public function itWillShowAInvoice() : void
    {
        $expense = Expense::factory()->create([
            'amount' => 100
        ]);

        $this
            ->getJson(route('expenses.show', [$expense]))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'id' => $expense->id,
                    'type' => 'invoice'
                ]
            ]);
    }

    /** @test */
    public function itWillDeleteAnExpense() : void
    {
        Carbon::setTestNow('now');
        $expense = Expense::factory()->create();

        $this
            ->deleteJson(route('expenses.show', [$expense]))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'deleted_at' => Carbon::now()
        ]);
    }

    /**
     * @test
     * @dataProvider amountProvider
     * @param int $amount
     */
    public function itWillCreateANewExpense(int $amount) : void
    {
        $payload = [
            'name' => 'expenses-name',
            'description' => 'expense-description',
            'amount' => $amount
        ];

        $this
            ->postJson(route('expenses.store'), $payload)
            ->assertSuccessful()
            ->assertJson([
                'data' => $payload
            ]);

        $this->assertDatabaseHas('expenses', $payload);
    }

    /**
     * @test
     * @dataProvider amountProvider
     * @param int $amount
     */
    public function itWillUpdateAnExpense(int $amount) : void
    {

        $expense = Expense::factory()->create();

        $payload = [
            'name' => 'updated-name',
            'description' => 'updated-description',
            'amount' => $amount
        ];

        $this
            ->putJson(route('expenses.update', [$expense]), $payload)
            ->assertSuccessful()
            ->assertJson([
                'data' => array_merge($payload, [
                    'id' => $expense->id
                ])
            ]);

        $this->assertDatabaseHas('expenses', array_merge($payload, [
            'id' => $expense->id
        ]));
    }

    public function amountProvider(): array
    {
        return [
            'positive' => [10000],
            'negative' => [-10000],
        ];
    }
}
