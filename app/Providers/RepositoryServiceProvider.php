<?php

namespace App\Providers;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use App\Repositories\Redis\ShoppingCartRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  
    public $singletons = [
        ProductRepositoryInterface::class => ProductRepository::class,
        ShoppingCartRepositoryInterface::class => ShoppingCartRepository::class,
    ];
}
