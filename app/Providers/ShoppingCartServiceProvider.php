<?php

namespace App\Providers;

use App\Services\Interfaces\ProcessShoppingCartServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ShoppingCartServiceInterface;
use App\Services\ProcessShoppingCartService;
use App\Services\ProductService;
use App\Services\ShoppingCartService;
use Illuminate\Support\ServiceProvider;

class ShoppingCartServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductServiceInterface::class => ProductService::class,
        ShoppingCartServiceInterface::class => ShoppingCartService::class,
        ProcessShoppingCartServiceInterface::class => ProcessShoppingCartService::class,
    ];
}
