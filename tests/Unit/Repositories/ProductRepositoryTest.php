<?php

namespace Tests\Unit\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{

    private static ProductRepositoryInterface $productRepository;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();
        self::$productRepository = new ProductRepository();
    }


    public function test_should_insert_a_new_product_in_table_and_return_id(): void
    {
        
        $productId = self::$productRepository->insert([
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
        
        $productId = self::$productRepository->insert([
            'name' => 'Other',
            'price' => 11.99,
            'description' => 'Other product'
        ]);


        $productData = self::$productRepository->get($productId);
        
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
        self::$productRepository->insert([
            'name' => 'Product1',
            'price' => 11.99,
            'description' => 'Other product 1'
        ]);

        self::$productRepository->insert([
            'name' => 'Product2',
            'price' => 18.33,
            'description' => 'Other product 2'
        ]);


        $products = self::$productRepository->getAll();
        
        $this->assertCount(2, $products);

    }

    public function test_should_delete_a_product_by_id(): void
    {
        $productId = self::$productRepository->insert([
            'name' => 'Product deleted',
            'price' => 15.99,
            'description' => 'This product is deleted'
        ]);

        
        self::$productRepository->delete($productId);
        
        $this->assertDatabaseHas(
            'products',
            [
                'name' => 'Product deleted',
                'price' => 15.99,
                'description' => 'This product is deleted',
                'deleted_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    public function test_should_updated_a_product(): void
    {
        $productId = self::$productRepository->insert([
            'name' => 'Outdated product',
            'price' => 55.44,
            'description' => 'This product is outdated'
        ]);

        self::$productRepository->update(
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
