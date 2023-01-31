<?php

namespace App\Services;

use App\Repositories\Interfaces\TransactionProductRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\ProcessShoppingCartServiceInterface;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProcessShoppingCartService implements ProcessShoppingCartServiceInterface {


    private ShoppingCartServiceInterface $shoppingCartService;
    private TransactionRepositoryInterface $transactionRepository;
    private TransactionProductRepositoryInterface $transactionProductRepository;

    public function __construct(
        ShoppingCartServiceInterface $shoppingCartService, 
        TransactionRepositoryInterface $transactionRepository,
        TransactionProductRepositoryInterface $transactionProductRepository,
    ) {
        $this->shoppingCartService = $shoppingCartService;
        $this->transactionRepository = $transactionRepository;
        $this->transactionProductRepository = $transactionProductRepository;
    }

    public function checkout(): array {

        $shoppingCart = $this->shoppingCartService->getProducts();

        if (!$shoppingCart['products']) {
            return [
                'processed' => false,
                'message' => 'The shopping cart is empty.',
                'totalAmount' => 0
            ];
        }

        $transactionId = $this->transactionRepository->insert([
            'user_id' => Auth::id(),
            'total_amount' => $shoppingCart['totalAmount']
        ]);

        foreach ($shoppingCart['products'] as $product) {
            $this->transactionProductRepository->insert([
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'transaction_id' => $transactionId
            ]);
        }

        $this->shoppingCartService->removeAllProducts();

        return [
            'processed' => true,
            'message' => 'The checkout was successfully processed.',
            'totalAmount' => $shoppingCart['totalAmount']
        ];

    }


}