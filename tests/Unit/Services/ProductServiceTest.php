<?php

namespace Tests\Unit\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\ProductService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{

    private ProductServiceInterface $productService;
    private MockInterface $mockProductRepository;

    public function setUp(): void {
        parent::setUp();

        $this->mockProductRepository = Mockery::mock(ProductRepositoryInterface::class);

        $this->productService = new ProductService($this->mockProductRepository);
    }

    public function tearDown(): void
    {
        parent::setUp();

        Mockery::close();

    }


    public function test_should_return_all_products(): void
    {

        $this->mockProductRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(['product1', 'product2']);

        $products = $this->productService->getAllProducts();

        $this->assertEquals(['product1', 'product2'], $products);

    }
    
    public function test_should_return_product_by_id(): void
    {

        $productId = 1;

        $this->mockProductRepository
            ->shouldReceive('get')
            ->once()
            ->andReturn(['id' => $productId, 'name' => 'Some product']);

        $product = $this->productService->getProduct($productId);

        $this->assertEquals(
            [
                'id' => 1, 
                'name' => 'Some product'
            ], 
            $product
        );

    }

    public function test_should_call_repository_insert_method_for_a_given_product(): void
    {
        $product = [
            'name' => 'Product name',
            'description' => 'Product name description',
            'price' => 55.22
        ];

        $this->mockProductRepository
            ->shouldReceive('insert')
            ->once()
            ->with($product)
            ->andReturn(99);

        $productId = $this->productService->insertProduct($product);

        $this->assertEquals(99, $productId);

    }

    public function test_should_call_repository_delete_method_for_a_given_product_id(): void
    {

        $productId = 99;

        $this->mockProductRepository
            ->shouldReceive('delete')
            ->once()
            ->with($productId)
            ->andReturn(true);

        $result = $this->productService->deleteProduct($productId);

        $this->assertTrue($result);

    }
    
    public function test_should_call_repository_update_method_for_a_given_product_id_and_new_product(): void
    {

        $productId = 99;

        $newProduct = [
            'name' => 'Product name',
            'description' => 'Product name description',
            'price' => 55.22
        ];

        $this->mockProductRepository
            ->shouldReceive('update')
            ->once()
            ->with($productId, $newProduct)
            ->andReturn(true);

        $result = $this->productService->updateProduct($productId, $newProduct);

        $this->assertTrue($result);

    }
}
