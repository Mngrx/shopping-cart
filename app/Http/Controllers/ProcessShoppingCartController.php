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

    public function processShoppingCart(): JsonResponse {
        $status = $this->processShoppingCartService->checkout();

        return response()->json($status, 200);
    }

}
