<?php

namespace Tests\Unit\Repositories\Redis;

use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Repositories\Redis\ShoppingCartRepository;
use Illuminate\Support\Facades\Redis;
use Tests\Mocks\RedisMock;
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
            ->with('sc_99', $data);

        $insertedKey = self::$shoppingCartRepository->insertOrUpdate($key, $data);

        
        $this->assertEquals('sc_99', $insertedKey);

    }
    
    public function test_should_remove_an_key_from_shopping_cart(): void
    {
        
        $key = 88;
        
        Redis::shouldReceive('del')
            ->once()
            ->with('sc_88');

        $removedKey = self::$shoppingCartRepository->delete($key);
        
        $this->assertEquals('sc_88', $removedKey);
        
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
            ->with('sc_77')
            ->andReturn($data);


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
}
