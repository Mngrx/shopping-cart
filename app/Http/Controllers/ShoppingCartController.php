<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToShoppingCartRequest;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
  
    private ShoppingCartServiceInterface $shoppingCartService;

    public function __construct(ShoppingCartServiceInterface $shoppingCartService) {
        $this->shoppingCartService = $shoppingCartService;
    }

    public function getShoppingCart(): JsonResponse
    {
        $shoppingCart = $this->shoppingCartService->getProducts();
        return response()->json($shoppingCart, 200);

    }

    public function storeProduct(AddProductToShoppingCartRequest $request): JsonResponse
    {
        $this->shoppingCartService->addProduct($request->productId, $request->quantity);
        return response()->json(['message' => 'Product added successfully.'], 201);
    }


    public function removeProduct($productId)
    {
        $this->shoppingCartService->removeProduct($productId);
        return response()->json(['message' => 'This product was removed from your shopping cart.'], 200);
    }

    
    public function clearShoppingCart()
    {
        $this->shoppingCartService->removeAllProducts();
        return response()->json(['message' => 'Your shopping cart is now empty.'], 200);
    }
}
