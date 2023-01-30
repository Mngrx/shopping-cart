<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use Illuminate\Support\Facades\Auth;

class ShoppingCartService implements ShoppingCartServiceInterface {


    private ShoppingCartRepositoryInterface $shoppingCartRepository;
    private ProductRepositoryInterface $productRepository;

    public function __construct(ShoppingCartRepositoryInterface $shoppingCartRepository, ProductRepositoryInterface $productRepository) {
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->productRepository = $productRepository;
    }

    private function addProductToShoppingCart(array $shoppingCart, array $product, int $quantity) {
        return array_merge($shoppingCart, [array_merge($product, ['quantity' => $quantity])]);
    }

    public function addProduct(int $productId, int $quantity): string { 
        
        $userId = Auth::id();

        $shoppingCart = $this->shoppingCartRepository->getByKey($userId);

        $product = $this->productRepository->get($productId);

        $shoppingCart = $this->addProductToShoppingCart($shoppingCart, $product, $quantity);

        $shoppingCartCode = $this->shoppingCartRepository->insertOrUpdate($userId, $shoppingCart);

        return $shoppingCartCode;
    
    }

    private function removeProductFromShoppingCart(array $shoppingCart, int $productId) {
        return array_filter($shoppingCart, function ($product) use ($productId) { return $product['id'] != $productId; });
    }

    public function removeProduct(int $productId): string {
    
        $userId = Auth::id();
        
        $shoppingCart = $this->shoppingCartRepository->getByKey($userId);

        $shoppingCart = $this->removeProductFromShoppingCart($shoppingCart, $productId);

        $shoppingCartCode = $this->shoppingCartRepository->insertOrUpdate($userId, $shoppingCart);

        return $shoppingCartCode;
    
    }

    public function getProducts(): array {
        $userId = Auth::id();
        return $this->shoppingCartRepository->getByKey($userId);
    }
    public function removeAllProducts(): bool {
        $userId = Auth::id();
        $this->shoppingCartRepository->delete($userId);
        return true;
    }

}
