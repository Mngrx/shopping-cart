<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Repositories\Eloquent\TransactionProductRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Services\Interfaces\ProcessShoppingCartServiceInterface;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use App\Services\ProcessShoppingCartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessShoppingCartServiceTest extends TestCase
{
    
    private ProcessShoppingCartServiceInterface $processShoppingCartService;
    private MockInterface $mockShoppingCartService;
    
    public function setUp(): void {
        parent::setUp();

        $this->mockShoppingCartService = Mockery::mock(ShoppingCartServiceInterface::class);

        $this->processShoppingCartService = new ProcessShoppingCartService(
            $this->mockShoppingCartService, 
            new TransactionRepository,
            new TransactionProductRepository
        );

    }

  
    public function test_should_call_user_current_shopping_cart_and_process_it(): void
    {
        $this
            ->mockShoppingCartService
            ->shouldReceive('getProducts')
            ->once()
            ->andReturn(
                [
                    'products' => [
                        ['id' => 1, 'name' => 'Soap', 'price' => 2.0, 'quantity' => 15],
                        ['id' => 3, 'name' => 'Shampoo', 'price' => 2.5, 'quantity' => 4],
                    ],
                    'totalAmount' => 40.0
                ]
            );

        $this
            ->mockShoppingCartService
            ->shouldReceive('removeAllProducts')
            ->once();

        Auth::shouldReceive('id')->andReturn(99);

        $result = $this->processShoppingCartService->checkout();

        $this->assertEquals(
            [
                'processed' => true,
                'message' => 'The checkout was successfully processed.',
                'totalAmount' => 40.0
            ],
            $result
        );

        $this->assertDatabaseHas(
            'transactions',
            [
                'id' => 1,
                'total_amount' => 40.0,
                'user_id' => 99
            ]
        );

        $this->assertDatabaseHas(
            'transaction_products',
            [
                'name' => 'Soap',
                'price' => 2.0,
                'quantity' => 15,
                'transaction_id' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'transaction_products',
            [
                'name' => 'Shampoo',
                'price' => 2.5,
                'quantity' => 4,
                'transaction_id' => 1,
            ]
        );

    }

    public function test_should_return_a_different_message_when_shopping_cart_is_empty(): void
    {
        $this
            ->mockShoppingCartService
            ->shouldReceive('getProducts')
            ->once()
            ->andReturn(
                [
                    'products' => [],
                    'totalAmount' => 0
                ]
            );

        $result = $this->processShoppingCartService->checkout();

        $this->assertEquals(
            [
                'processed' => false,
                'message' => 'The shopping cart is empty.',
                'totalAmount' => 0
            ],
            $result
        );

    }
}
