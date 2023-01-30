<?php

namespace App\Services\Interfaces;

interface ProductServiceInterface {

    public function getAllProducts(): array;
    public function getProduct(int $productId): array;
    public function insertProduct(array $product): int;
    public function deleteProduct(int $productId): bool;
    public function updateProduct(int $productId, array $newProduct): bool;
}