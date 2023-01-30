<?php

namespace App\Providers;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\TransactionProductRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Repositories\Interfaces\TransactionProductRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Redis\ShoppingCartRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  
    public $singletons = [
        ProductRepositoryInterface::class => ProductRepository::class,
        ShoppingCartRepositoryInterface::class => ShoppingCartRepository::class,
        TransactionRepositoryInterface::class => TransactionRepository::class,
        TransactionProductRepositoryInterface::class => TransactionProductRepository::class,
    ];
}
