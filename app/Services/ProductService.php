<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;

class ProductService implements ProductServiceInterface {

    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): array 
    {
        return $this->productRepository->getAll();
    }

    public function getProduct(int $productId): array 
    {
        return $this->productRepository->get($productId);
    }

    public function insertProduct(array $product): int
    {
        return $this->productRepository->insert($product);
    }

    public function deleteProduct(int $productId): bool
    {
        return $this->productRepository->delete($productId);
    }

    public function updateProduct(int $productId, array $newProduct): bool
    {
        return $this->productRepository->update($productId, $newProduct);
    }

}