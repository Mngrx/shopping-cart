<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface {

    public function insert(array $product): int;
    public function get(int $productId): array;
    public function getAll(): array;
    public function delete(int $productId): bool;
    public function update(int $productId, array $newProduct): bool;
    
}