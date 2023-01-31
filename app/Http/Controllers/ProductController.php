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

    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getProductsList",
     *      tags={"Products"},
     *      summary="Get list of all products",
     *      description="Returns list of all products",
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
    public function index(): JsonResponse
    {

        $products = $this->productService->getAllProducts();
        return response()->json($products, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/products",
     *      operationId="storeProduct",
     *      tags={"Products"},
     *      summary="Store a new product",
     *      description="A admin user can insert a product",
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
     *          description="Forbidden - you need to be a admin user"
     *      )
     *     )
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $productId = $this->productService->insertProduct($request->validated());
        return response()->json(['productId' => $productId], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getProductById",
     *      tags={"Products"},
     *      summary="Get product information",
     *      description="Returns product data for a given ID",
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
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show(int $productId): JsonResponse
    {
        try {
            $product = $this->productService->getProduct($productId);
            return response()->json($product, 200);
        } catch (\Error $e) {
            return response()->json([], 404);
        }
    }

/**
     * @OA\Put(
     *      path="/api/products/{id}",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      summary="Update existing product",
     *      description="A admin user can update a product",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
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
     *          description="Forbidden - you need to be a admin user"
     *      )
     * )
     */
    public function update(UpdateProductRequest $request, int $productId): JsonResponse
    {
        $isUpdated = $this->productService->updateProduct($productId, $request->validated());
        return response()->json(['productUpdated' => $isUpdated], 200);
    }

/**
     * @OA\Delete(
     *      path="/api/products/{id}",
     *      operationId="deleteProduct",
     *      tags={"Products"},
     *      summary="Delete existing product",
     *      description="A admin user can delete a product",
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
     *          description="Forbidden - you need to be a admin user"
     *      ),
     * )
     */
    public function destroy(int $productId): JsonResponse
    {
        $isDeleted = $this->productService->deleteProduct($productId);
        return response()->json(['productDeleted' => $isDeleted], 200);    
    }
}
