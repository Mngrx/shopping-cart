<?php

namespace Tests\Unit\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use App\Services\ShoppingCartService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShoppingCartServiceTest extends TestCase
{
    private ShoppingCartServiceInterface $shoppingCartService;
    private MockInterface $mockProductRepository;
    private MockInterface $mockShoppingCartRepository;

    public function setUp(): void {
        parent::setUp();

        $this->mockShoppingCartRepository = Mockery::mock(ShoppingCartRepositoryInterface::class);
        $this->mockProductRepository = Mockery::mock(ProductRepositoryInterface::class);

        $this->shoppingCartService = new ShoppingCartService($this->mockShoppingCartRepository, $this->mockProductRepository);
    }

    public function tearDown(): void
    {
        parent::setUp();

        Mockery::close();

    }

    public function test_should_add_a_product_to_shopping_cart(): void {

        
        $productId = 2;
        $quantity = 3;
        $userId = 55;

        Auth::shouldReceive('id')->once()->andReturn($userId);
        
        $this
            ->mockShoppingCartRepository
            ->shouldReceive('getByKey')
            ->once()
            ->with($userId)
            ->andReturn([]);

        $this
            ->mockProductRepository
            ->shouldReceive('get')
            ->once()
            ->with(2)
            ->andReturn(['id' => 2, 'name' => 'Some Product', 'price' => 7.77]);

        $this
            ->mockShoppingCartRepository
            ->shouldReceive('insertOrUpdate')
            ->once()
            ->with($userId, [['id' => 2, 'name' => 'Some Product', 'price' => 7.77, 'quantity' => 3]])
            ->andReturn('sc:55');

        $shoppingCartCode = $this->shoppingCartService->addProduct($productId, $quantity);

        $this->assertEquals('sc:55', $shoppingCartCode);

    }
    
    public function test_should_remove_a_product_from_the_shopping_cart(): void {

        $productId = 3;
        $userId = 66;

        Auth::shouldReceive('id')->once()->andReturn($userId);

        $this
            ->mockShoppingCartRepository
            ->shouldReceive('getByKey')
            ->once()
            ->with($userId)
            ->andReturn([
                ['id' => 2, 'name' => 'Some Product', 'price' => 7.77, 'quantity' => 3],
                ['id' => 3, 'name' => 'Other Product', 'price' => 9.99, 'quantity' => 2]
            ]);
        
        $this
            ->mockShoppingCartRepository
            ->shouldReceive('insertOrUpdate')
            ->once()
            ->with($userId, [['id' => 2, 'name' => 'Some Product', 'price' => 7.77, 'quantity' => 3]])
            ->andReturn('sc:66');

        $shoppingCartCode = $this->shoppingCartService->removeProduct($productId);

        $this->assertEquals('sc:66', $shoppingCartCode);

    }
    
    public function test_should_do_nothing_when_try_to_remove_a_product_that_is_not_in_shopping_cart(): void {

        $productId = 3;
        $userId = 77;

        Auth::shouldReceive('id')->once()->andReturn($userId);

        $this
            ->mockShoppingCartRepository
            ->shouldReceive('getByKey')
            ->once()
            ->with($userId)
            ->andReturn([]);
        
        $this
            ->mockShoppingCartRepository
            ->shouldReceive('insertOrUpdate')
            ->once()
            ->with($userId, [])
            ->andReturn('sc:77');

        $shoppingCartCode = $this->shoppingCartService->removeProduct($productId);

        $this->assertEquals('sc:77', $shoppingCartCode);

    }
    
    public function test_should_get_all_products_from_shopping_cart(): void {

        $userId = 77;

        Auth::shouldReceive('id')->once()->andReturn($userId);

        $this
            ->mockShoppingCartRepository
            ->shouldReceive('getByKey')
            ->once()
            ->with($userId)
            ->andReturn([
                ['id' => 2, 'name' => 'Some Product', 'price' => 10.0, 'quantity' => 3],
                ['id' => 3, 'name' => 'Other Product', 'price' => 9.0, 'quantity' => 2]
            ]);
        

        $shoppingCart = $this->shoppingCartService->getProducts();

        $this->assertEquals(
            [
                'products' => [
                    ['id' => 2, 'name' => 'Some Product', 'price' => 10.0, 'quantity' => 3],
                    ['id' => 3, 'name' => 'Other Product', 'price' => 9.0, 'quantity' => 2]
                ],
                'totalAmount' => 48.0
            ], 
            $shoppingCart
        );

    }

    public function test_should_remove_all_products_from_shopping_cart(): void {

        $userId = 77;

        Auth::shouldReceive('id')->once()->andReturn($userId);

        $this
            ->mockShoppingCartRepository
            ->shouldReceive('delete')
            ->once()
            ->with($userId)
            ->andReturn('sc:77');
        

        $shoppingCartEmpty = $this->shoppingCartService->removeAllProducts();

        $this->assertTrue($shoppingCartEmpty);

    }

}
