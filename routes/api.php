<?php

use App\Http\Controllers\Auth\AuthContoller;
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
});
