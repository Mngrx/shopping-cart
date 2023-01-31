<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    private User $user;

    public function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create(['admin' => true]);

        DB::table('products')
            ->insert(
                [
                    [
                        'name' => 'Soap',
                        'description' => 'To take a shower.',
                        'price' => 1.99,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'name' => 'Towel',
                        'description' => 'Use the towel to dry.',
                        'price' => 12.50,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'name' => 'Shampoo',
                        'description' => 'For hair washing.',
                        'price' => 8.99,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                ]
            );
    }

    public function test_should_return_201_add_an_product_with_quantity_to_shopping_cart()
    {
        $request = [
            'productId' => 1,
            'quantity' => 2
        ];

        Redis::shouldReceive('get')
            ->once();
        Redis::shouldReceive('set')
            ->once();

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('api.shopping-cart.store'), $request);

        $response->assertStatus(201);
    }
    
    public function test_should_return_201_and_update_product_that_already_is_in_shopping_cart()
    {
        $firstRequest = [
            'productId' => 1,
            'quantity' => 2
        ];

        $secondRequest = [
            'productId' => 1,
            'quantity' => 6
        ];

        Redis::shouldReceive('get')
            ->twice();
        
        Redis::shouldReceive('set')
            ->twice();

        $firstResponse = $this
            ->actingAs($this->user)
            ->postJson(route('api.shopping-cart.store'), $firstRequest);
        
        $secondResponse = $this
            ->actingAs($this->user)
            ->postJson(route('api.shopping-cart.store'), $secondRequest);

        $firstResponse->assertStatus(201);
        $secondResponse->assertStatus(201);
    }

    public function test_should_return_422_status_when_send_a_product_id_that_does_not_exist()
    {
        $request = [
            'productId' => 99,
            'quantity' => 2
        ];


        $response = $this
            ->actingAs($this->user)
            ->postJson(route('api.shopping-cart.store'), $request);
        
           
        $response->assertStatus(422);
    }

    public function test_should_return_200_status_when_remove_all_products_from_shopping_cart()
    {
        Redis::shouldReceive('del')
            ->once();

        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('api.shopping-cart.clear'));
        
        $response->assertStatus(200);

    }

    public function test_should_return_200_status_when_remove_a_product_from_shopping_cart()
    {

        $request = [
            'productId' => 1,
            'quantity' => 2
        ];

        Redis::shouldReceive('get')
            ->twice();
        Redis::shouldReceive('set')
            ->twice();

        $this
            ->actingAs($this->user)
            ->postJson(route('api.shopping-cart.store'), $request);


        $deleteResponse = $this
            ->actingAs($this->user)
            ->deleteJson(route('api.shopping-cart.remove', 1));
        
        $deleteResponse->assertStatus(200);

    }

    public function test_should_return_200_status_total_amount_and_all_products_when_get_all_product_from_shopping_cart()
    {

        Redis::shouldReceive('get')
            ->once()
            ->andReturn(json_encode([
                    ['id' => 2, 'name' => 'Towel', 'price' => 5.0, 'quantity' => 2],
                    ['id' => 3, 'name' => 'Shampoo', 'price' => 4.0, 'quantity' => 1]
            ]));

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.shopping-cart.get'));


        $response->assertStatus(200);
        $response->assertJson([
            'products' => [
                ['id' => 2, 'name' => 'Towel', 'price' => 5.0, 'quantity' => 2],
                ['id' => 3, 'name' => 'Shampoo', 'price' => 4.0, 'quantity' => 1]
            ],
            'totalAmount' => 14.0
        ]);

    }
}
