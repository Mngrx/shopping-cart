<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function insert(array $product): int {
        $product = Product::create($product);
        
        return $product->id;

    }

    public function get(int $productId): array {

        return Product::find($productId)->toArray();

    }

    public function getAll(): array {

        return Product::all()->toArray();

    }

    public function delete(int $productId): bool {
        
        Product::destroy($productId);
        return true;
    }
    
    public function update(int $productId, array $newProduct): bool {

        Product::whereId($productId)->update($newProduct);
        return true;
    }
}
