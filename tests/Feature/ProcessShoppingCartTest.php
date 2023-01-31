<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ProcessShoppingCartTest extends TestCase
{

    private User $user;

    public function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create(['id' => 99, 'admin' => true]);
    }
    
    public function test_should_return_200_status_and_create_transaction_entry_when_shopping_cart_is_not_empty()
    {

        Redis::shouldReceive('get')
            ->once()
            ->with('sc:'.$this->user->id)
            ->andReturn(
                json_encode(
                    [
                        ['id' => 1, 'name' => 'Soap', 'price' => 2.0, 'quantity' => 15],
                        ['id' => 3, 'name' => 'Shampoo', 'price' => 2.5, 'quantity' => 4]
                    ]
                )
            );
        
        Redis::shouldReceive('del')
            ->once()
            ->with('sc:'.$this->user->id);


        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.shopping-cart.process'));

        $response->assertStatus(200);
        
        $response->assertJson(
            [
                'processed' => true
            ]
        );

        $this->assertDatabaseHas(
            'transactions',
            [
                'id' => 1,
                'total_amount' => 40.0,
                'user_id' => 99
            ]
        );
    }
    
    public function test_should_return_200_status_and_the_right_message_when_shopping_cart_is_empty()
    {
        Redis::shouldReceive('get')
            ->once()
            ->with('sc:'.$this->user->id)
            ->andReturn([]);


        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.shopping-cart.process'));

        $response->assertStatus(200);

        $response->assertJson(
            [
                'processed' => false
            ]
        );

        $this->assertDatabaseEmpty(
            'transactions'
        );
    }
}
