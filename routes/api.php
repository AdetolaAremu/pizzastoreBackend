<?php

use App\Http\Controllers\Auth\AuthContoller;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PaymentController\PaymentController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Pizza\PizzaController;
use App\Http\Controllers\Pizza\PizzaReviewController;
use App\Http\Controllers\Pizza\VariantController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthContoller::class, 'register']);

Route::post('/login', [AuthContoller::class, 'login']);

// all pizzas
Route::get('/all-pizzas', [PizzaController::class, 'index']);
Route::get('/pizza/{id}', [PizzaController::class, 'show']);

Route::group(['middleware' => 'auth:api'], function () {
    // logout
    Route::post('/logout', [AuthContoller::class, 'logout']);

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
        Route::put('/info', [UserController::class, 'updateInfo']);
        Route::put('/password', [UserController::class, 'updatePassword']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // pizzas
    Route::group(['prefix' =>  'pizzas'], function () {
        Route::post('/', [PizzaController::class, 'store']);
        Route::put('/{id}', [PizzaController::class, 'update']);
        Route::delete('/{id}', [PizzaController::class, 'destroy']);
    });

    // Carts
    Route::group(['prefix' => 'carts'], function () {
        Route::post('/', [CartController::class, 'addToCart']);
        Route::get('/', [CartController::class, 'getcart']);
        Route::put('/update-cart/{cart_item_id}', [CartController::class, 'updatecart']);
        Route::get('/{id}', [CartController::class, 'getSpecificCart']);
        Route::delete('/empty', [CartController::class, 'emptyCart']);
    });
    Route::delete('/cart-item/{cart_item_id}', [CartController::class, 'removecart']);

    // orders
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'getAllOrders']);
        Route::get('/with-items/{id}', [OrderController::class, 'getOrderWithItems']);
        Route::get('/last-five-orders', [OrderController::class, 'lastFiveOrders']);
        Route::get('/stats', [OrderController::class, 'getOrderStats']);
        Route::get('/export', [OrderController::class, 'exportcsv']);
    });

    // roles
    Route::apiResource('roles', RoleController::class);

    // permissions
    Route::apiResource('permissions', PermissionController::class)->only('show');

    // payment
    Route::post('/checkout-paystack', [PaymentController::class, 'initialize_payment']);
});


// finalize paystack payment
Route::get('/finalize_payment', [PaymentController::class, 'finalize_payment']);    