<?php

namespace App\Providers;

use App\Services\Interfaces\ProductServiceInterface;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class ShoppingCartServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductServiceInterface::class => ProductService::class,
    ];
}
