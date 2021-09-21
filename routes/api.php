<?php

use App\Http\Controllers\Auth\AuthContoller;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Pizza\PizzaController;
use App\Http\Controllers\Pizza\PizzaReviewController;
use App\Http\Controllers\Pizza\VariantController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthContoller::class, 'register']);

Route::post('/login', [AuthContoller::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    // pizza variants
    Route::apiResource('/variants', VariantController::class);

    // review pizza
    Route::apiResource('/pizza-review', PizzaReviewController::class);

    // get logged in user
    Route::get('/user', [UserController::class, 'currentUser']);

    // users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'updateInfo']);
        Route::put('/{id}', [UserController::class, 'updatePassword']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // pizzas
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
        Route::delete('/empty', [CartController::class, 'emptyCart']);
    });

    // orders
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'orderHistory']);
        Route::get('/export', [OrderController::class, 'exportcsv']);
    });

    // roles
    Route::apiResource('roles', RoleController::class);

    // permissions
    Route::apiResource('permissions', PermissionController::class)->only('show');
});
