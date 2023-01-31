<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ProcessShoppingCartServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessShoppingCartController extends Controller
{

    private ProcessShoppingCartServiceInterface $processShoppingCartService;
    
    public function __construct(ProcessShoppingCartServiceInterface $processShoppingCartService) {
        $this->processShoppingCartService = $processShoppingCartService;
    }

    /**
     * @OA\Get(
     *      path="/api/shopping-cart/checkout",
     *      operationId="checkoutShoppingCart",
     *      tags={"Process Shopping Cart"},
     *      summary="Trigger shopping cart checkout operation",
     *      description="Create a transaction and save the itens that are in the current shopping cart and finish it",
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
    public function processShoppingCart(): JsonResponse {
        $status = $this->processShoppingCartService->checkout();

        return response()->json($status, 200);
    }

}
