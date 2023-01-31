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

    private function addProductToShoppingCart(array $shoppingCart, array $product, int $quantity): array {
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

    private function removeProductFromShoppingCart(array $shoppingCart, int $productId): array {
        return array_filter($shoppingCart, function ($product) use ($productId) { return $product['id'] != $productId; });
    }

    public function removeProduct(int $productId): string {
    
        $userId = Auth::id();
        
        $shoppingCart = $this->shoppingCartRepository->getByKey($userId);

        $shoppingCart = $this->removeProductFromShoppingCart($shoppingCart, $productId);

        $shoppingCartCode = $this->shoppingCartRepository->insertOrUpdate($userId, $shoppingCart);

        return $shoppingCartCode;
    
    }


    private function calculateTotalAmount(array $shoppingCart): float {

        $totalAmount = 0.0;

        foreach ($shoppingCart as $item) {
            $totalAmount += ($item["quantity"] * $item["price"]);
        }

        return $totalAmount;

    }

    public function getProducts(): array {
        $userId = Auth::id();

        $shoppingCart = $this->shoppingCartRepository->getByKey($userId);

        return [
            'products' => $shoppingCart,
            'totalAmount' => $this->calculateTotalAmount($shoppingCart)
        ];
    }


    public function removeAllProducts(): bool {
        $userId = Auth::id();
        $this->shoppingCartRepository->delete($userId);
        return true;
    }

}
