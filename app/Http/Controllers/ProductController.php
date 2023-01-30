<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{

    private ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService) {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {

        $products = $this->productService->getAllProducts();
        return response()->json($products, 200);
    }


    public function store(StoreProductRequest $request): JsonResponse
    {
        $productId = $this->productService->insertProduct($request->validated());
        return response()->json(['productId' => $productId], 201);
    }


    public function show(int $productId): JsonResponse
    {
        try {
            $product = $this->productService->getProduct($productId);
            return response()->json($product, 200);
        } catch (\Error $e) {
            return response()->json([], 404);
        }
    }


    public function update(UpdateProductRequest $request, int $productId): JsonResponse
    {
        $isUpdated = $this->productService->updateProduct($productId, $request->validated());
        return response()->json(['productUpdated' => $isUpdated], 200);
    }


    public function destroy(int $productId): JsonResponse
    {
        $isDeleted = $this->productService->deleteProduct($productId);
        return response()->json(['productDeleted' => $isDeleted], 200);    
    }
}
