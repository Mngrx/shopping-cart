<?php

namespace Tests\Unit;

use App\Repositories\ProductRepository;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{

    private ProductRepository $productRepository;

    public function setUp(): void {
        parent::setUp();
        $this->productRepository = new ProductRepository();
    }
    
    public function test_should_insert_a_new_product_in_table_and_return_id(): void
    {
        
        $productId = $this->productRepository->insert([
            'name' => 'Product Name',
            'price' => 99.99,
            'description' => 'Something to describe the product'
        ]);

        $this->assertDatabaseHas(
            'products',
            [
                'name' => 'Product Name',
                'price' => 99.99,
                'description' => 'Something to describe the product'
            ]
        );

        $this->assertIsInt($productId);

    }

    public function test_should_get_product_as_array_by_id(): void
    {
        
        $productId = $this->productRepository->insert([
            'name' => 'Other',
            'price' => 11.99,
            'description' => 'Other product'
        ]);


        $productData = $this->productRepository->get($productId);
        
        $this->assertEquals(
            [
                'id' => $productData['id'],
                'name' => $productData['name'],
                'price' => $productData['price'],
                'description' => $productData['description'],
            ],
            [
                'id' => $productId,
                'name' => 'Other',
                'price' => 11.99,
                'description' => 'Other product'
            ]
        );

    }

    public function test_should_get_all_products(): void
    {
        $this->productRepository->insert([
            'name' => 'Product1',
            'price' => 11.99,
            'description' => 'Other product 1'
        ]);

        $this->productRepository->insert([
            'name' => 'Product2',
            'price' => 18.33,
            'description' => 'Other product 2'
        ]);


        $products = $this->productRepository->getAll();
        
        $this->assertCount(2, $products);

    }

    public function test_should_delete_a_product_by_id(): void
    {
        $productId = $this->productRepository->insert([
            'name' => 'Product deleted',
            'price' => 15.99,
            'description' => 'This product is deleted'
        ]);

        
        $this->productRepository->delete($productId);
        
        $this->assertDatabaseMissing(
            'products',
            [
                'name' => 'Product deleted',
                'price' => 15.99,
                'description' => 'This product is deleted'
            ]
        );
    }

    public function test_should_updated_a_product(): void
    {
        $productId = $this->productRepository->insert([
            'name' => 'Outdated product',
            'price' => 55.44,
            'description' => 'This product is outdated'
        ]);

        $this->productRepository->update(
            $productId,
            [
                'name' => 'New name',
                'price' => 22.22,
                'description' => 'This product was updated'
            ]
        );


        $this->assertDatabaseMissing(
            'products',
            [
                'name' => 'Outdated product',
                'price' => 55.44,
                'description' => 'This product is outdated'
            ]
        );

        $this->assertDatabaseHas(
            'products',
            [
                'name' => 'New name',
                'price' => 22.22,
                'description' => 'This product was updated'
            ]
        );

    }
}
