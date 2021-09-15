<?php

use App\Http\Controllers\Auth\AuthContoller;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Pizza\PizzaController;
use App\Http\Controllers\Pizza\PizzaReviewController;
use App\Http\Controllers\Pizza\VariantController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthContoller::class, 'register']);

Route::post('/login', [AuthContoller::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/variants', VariantController::class);
    Route::apiResource('/pizza-review', PizzaReviewController::class);
    Route::apiResource('/pizzas', PizzaController::class);

    Route::group(['prefix' =>  'pizzas'], function () {
        Route::get('/', [PizzaController::class, 'index']);
        Route::post('/', [PizzaController::class, 'store']);
        Route::get('/{id}', [PizzaController::class, 'show']);
        Route::put('/{id}', [PizzaController::class, 'update']);
        Route::delete('/{id}', [PizzaController::class, 'destroy']);
    });

    // Carts
    Route::group(['prefix' => 'carts'], function () {
        Route::post('/', [CartController::class, 'addToCart']);
        Route::get('/', [CartController::class, 'getcart']);
        Route::get('/{id}', [CartController::class, 'getSpecificCart']);
        Route::get('/{id}', [CartController::class, 'emptyCart']);
    });
});
