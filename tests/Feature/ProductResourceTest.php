<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductResourceTest extends TestCase
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
    
    public function test_should_return_200_code_and_a_list_of_products(): void
    {
        
        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.products.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }
    
    public function test_should_return_200_code_and_the_product_of_the_given_id(): void
    {
        
        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.products.show', 1));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Soap',
            'description' => 'To take a shower.',
            'price' => 1.99
        ]);
    }
    
    public function test_should_return_404_code_and_empty_object_for_the_given_nonexistent_id(): void
    {
        
        $response = $this
            ->actingAs($this->user)
            ->getJson(route('api.products.show', 99));

        $response->assertStatus(200);
        $response->assertJson([]);
    }
    
    public function test_should_return_201_code_and_return_the_new_product_id(): void
    {
        
        $response = $this
            ->actingAs($this->user)
            ->postJson(
                route('api.products.store'),
                [
                    'name' => 'Conditioner',
                    'price' => 7.99,
                    'description' => 'Use after the shampoo.'
                ]
            );

        $response->assertStatus(201);
        $response->assertJson(['productId' => 4]);
    }
    
    public function test_should_return_422_code_when_try_to_insert_a_product_with_missing_fields(): void
    {
        
        $response = $this
            ->actingAs($this->user)
            ->postJson(
                route('api.products.store'),
                [
                    'price' => 7.99,
                    'description' => 'Use after the shampoo.'
                ]
            );

        $response->assertStatus(422);
    }
    
    public function test_should_return_422_code_when_try_to_insert_a_product_with_invalid_values(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->postJson(
                route('api.products.store'),
                [
                    'name' => '',
                    'price' => 7.99,
                    'description' => 'Use after the shampoo.'
                ]
            );

        $response->assertStatus(422);
    }
    
    public function test_should_return_403_code_when_try_to_insert_a_product_and_the_user_is_not_admin(): void
    {

        $this->user->admin = false;

        $response = $this
            ->actingAs($this->user)
            ->postJson(
                route('api.products.store'),
                [
                    'name' => 'Conditioner',
                    'price' => '7.99',
                    'description' => 'Use after the shampoo.'
                ]
            );

        $response->assertStatus(403);
    }

    public function test_should_return_200_code_when_update_a_product_successfully(): void
    {

        $updateResponse = $this
            ->actingAs($this->user)
            ->putJson(
                route('api.products.update', 2),
                [
                    'description' => 'Use the towel to dry your hair.',
                ]
            );


        $getResponse = $this
            ->actingAs($this->user)
            ->getJson(route('api.products.show', 2));

        $updateResponse->assertStatus(200);

        $getResponse->assertJson(
            [
                'description' => 'Use the towel to dry your hair.',
            ]
        );
    }

    
    public function test_should_return_403_code_when_an_non_admin_user_try_to_update_a_product(): void
    {
        $this->user->admin = false;

        $response = $this
            ->actingAs($this->user)
            ->putJson(
                route('api.products.update', 2),
                [
                    'description' => 'Use the towel to dry your hair.',
                ]
            );


        $response->assertStatus(403);
        
    }
    
    public function test_should_return_403_code_when_an_non_admin_user_try_to_delete_a_product(): void
    {
        $this->user->admin = false;

        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('api.products.destroy', 2));

        $response->assertStatus(403);
        
    }
}
