<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::name('products.')->group(function() {
        Route::get('products', [ProductController::class, 'index'])->name('index');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('show');
        Route::post('products', [ProductController::class, 'store'])->name('store');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('destroy')->middleware('is_admin');
    });

});



