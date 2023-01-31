<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProcessShoppingCartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/login/admin', [LoginController::class, 'loginAdmin']);
Route::get('/login/regular', [LoginController::class, 'loginRegular']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::name('products.')->group(function() {
        Route::get('products', [ProductController::class, 'index'])->name('index');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('show');
        Route::post('products', [ProductController::class, 'store'])->name('store')->middleware('is_admin');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('update')->middleware('is_admin');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('destroy')->middleware('is_admin');
    });

    Route::name('shopping-cart.')->group(function() {
        Route::post('shopping-cart', [ShoppingCartController::class, 'storeProduct'])->name('store');
        Route::get('shopping-cart', [ShoppingCartController::class, 'getShoppingCart'])->name('get');
        Route::delete('shopping-cart', [ShoppingCartController::class, 'clearShoppingCart'])->name('clear');
        Route::delete('shopping-cart/{id}', [ShoppingCartController::class, 'removeProduct'])->name('remove');

        Route::get('shopping-cart/checkout', [ProcessShoppingCartController::class, 'processShoppingCart'])->name('process');

    });

});



