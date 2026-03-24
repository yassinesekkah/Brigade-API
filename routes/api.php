<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    ///===> Category Routes  <===\\\
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::middleware('admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        ///===> Ingredient Routes  <===\\\
        Route::get('/ingredients', [IngredientController::class, 'index']);
        Route::post('/ingredients', [IngredientController::class, 'store']);
        Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update']);
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy']);
    });

    ///===> Plats Routes  <===\\\
    Route::get('/plats', [PlatController::class, 'index']);
    Route::get('/plats/{plat}', [PlatController::class, 'show']);
    Route::get('/categories/{category}/plats', [PlatController::class, 'platsByCategory']);

    Route::middleware('admin')->group(function () {
        Route::post('/plats', [PlatController::class, 'store']);
        Route::put('/plats/{plat}', [PlatController::class, 'update']);
        Route::delete('/plats/{plat}', [PlatController::class, 'destroy']);
    });

    ///===> Restaurant Routes  <===\\\
    Route::middleware('admin')->group(function () {
        Route::post('/restaurants', [RestaurantController::class, 'store']);
    });

    Route::get('/restaurants/me', [RestaurantController::class, 'me']);


    ///===> Recommendation Routes  <===\\\
    Route::post('/recommendations/analyze/{plat}',[RecommendationController::class, 'analyze']);
    Route::get('/recommendations/{recommendation}', [RecommendationController::class, 'show']);
});
