<?php

namespace Tests\Unit\Repositories\Redis;

use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Repositories\Redis\ShoppingCartRepository;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ShoppingCartRepositoryTest extends TestCase
{
    
    private static ShoppingCartRepositoryInterface $shoppingCartRepository;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();
        self::$shoppingCartRepository = new ShoppingCartRepository();
    }

    public function test_should_insert_an_key_and_data_in_shopping_cart()
    {

        $key = 99;
        $data = [
            [
                'product_id' => 1,
                'price' => 19.99,
                'name' => 'Good Product'
            ]
        ];

        Redis::shouldReceive('set')
            ->once()
            ->with('sc:99', json_encode($data));

        $insertedKey = self::$shoppingCartRepository->insertOrUpdate($key, $data);

        
        $this->assertEquals('sc:99', $insertedKey);

    }
    
    public function test_should_remove_an_key_from_shopping_cart(): void
    {
        
        $key = 88;
        
        Redis::shouldReceive('del')
            ->once()
            ->with('sc:88');

        $removedKey = self::$shoppingCartRepository->delete($key);
        
        $this->assertEquals('sc:88', $removedKey);
        
    }
    
    public function test_should_get_an_data_by_key_from_shopping_cart(): void
    {

        $key = 77;
        $data = [
            [
                'product_id' => 1,
                'price' => 19.99,
                'name' => 'Good Product'
            ]
        ];
        
        Redis::shouldReceive('get')
            ->once()
            ->with('sc:77')
            ->andReturn(json_encode($data));


        $result = self::$shoppingCartRepository->getByKey($key);
    
        $this->assertEquals(
            [
                [
                    'product_id' => 1,
                    'price' => 19.99,
                    'name' => 'Good Product'
                ]
            ],
            $result
        );

    }
    
    public function test_should_return_empty_array_when_try_to_get_nonexistent_key_from_shopping_cart(): void
    {

        $key = 77;
        
        Redis::shouldReceive('get')
            ->once()
            ->with('sc:77')
            ->andReturnNull();


        $result = self::$shoppingCartRepository->getByKey($key);
    
        $this->assertEquals([], $result);

    }
}
