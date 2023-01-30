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

    public function setUp(): void {
        
        parent::setUp();

        $factory = new \M6Web\Component\RedisMock\RedisMockFactory();
        $myRedisMock = $factory->getAdapter(Redis::class);
        Redis::swap($myRedisMock);
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

        $insertedKey = self::$shoppingCartRepository->insertOrUpdate($key, $data);

        
        $this->assertEquals('sc_99', $insertedKey);
        // TODO: verify if it is in Redis
    }
    
    public function test_should_remove_an_key_from_shopping_cart(): void
    {

        $key = 88;
        
        $removedKey = self::$shoppingCartRepository->delete($key);

        
        $this->assertEquals('sc_88', $removedKey);
        // TODO: verify if it is not in Redis
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
        Redis::set('sc_77', $data);

        self::$shoppingCartRepository->getByKey($key);
        
        // TODO: assert if the function is returning the correct data
    }
}
