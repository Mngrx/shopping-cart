<?php

namespace App\Services\Interfaces;

interface ShoppingCartServiceInterface {

    public function addProduct(int $productId, int $quantity): string;
    public function removeProduct(int $productId): string;
    public function getProducts(): array;
    public function removeAllProducts(): bool;

}