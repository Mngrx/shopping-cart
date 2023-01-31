<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToShoppingCartRequest;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use Illuminate\Http\JsonResponse;

class ShoppingCartController extends Controller
{
  
    private ShoppingCartServiceInterface $shoppingCartService;

    public function __construct(ShoppingCartServiceInterface $shoppingCartService) {
        $this->shoppingCartService = $shoppingCartService;
    }

    /**
     * @OA\Get(
     *      path="/api/shopping-cart/",
     *      operationId="getShoppingCart",
     *      tags={"Shopping Cart"},
     *      summary="Return products that are in shopping cart",
     *      description="Return products that are in shopping cart and the total amount",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function getShoppingCart(): JsonResponse
    {
        $shoppingCart = $this->shoppingCartService->getProducts();
        return response()->json($shoppingCart, 200);

    }

    /**
     * @OA\Post(
     *      path="/api/shopping-cart/",
     *      operationId="storeProductinShoppingCart",
     *      tags={"Shopping Cart"},
     *      summary="Insert a product in shopping cart",
     *      description="Insert a product in shopping cart by the given ID and an quantity value",
     *      @OA\RequestBody(
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function storeProduct(AddProductToShoppingCartRequest $request): JsonResponse
    {
        $this->shoppingCartService->addProduct($request->productId, $request->quantity);
        return response()->json(['message' => 'Product added successfully.'], 201);
    }

    /**
     * @OA\Delete(
     *      path="/api/shopping-cart/{id}",
     *      operationId="removeProductFromShoppingCart",
     *      tags={"Shopping Cart"},
     *      summary="Remove a product from shopping cart",
     *      description="Remove a specific product from shopping cart",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */
    public function removeProduct($productId)
    {
        $this->shoppingCartService->removeProduct($productId);
        return response()->json(['message' => 'This product was removed from your shopping cart.'], 200);
    }

   /**
     * @OA\Delete(
     *      path="/api/shopping-cart",
     *      operationId="clearShoppingCart",
     *      tags={"Shopping Cart"},
     *      summary="Clear the user shopping cart",
     *      description="Remove all products from users shopping cart",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */
    public function clearShoppingCart()
    {
        $this->shoppingCartService->removeAllProducts();
        return response()->json(['message' => 'Your shopping cart is now empty.'], 200);
    }
}
