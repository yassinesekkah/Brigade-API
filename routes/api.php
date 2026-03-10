<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlatController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

///===> Category Routes  <===\\\

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

///===> Plats Routes  <===\\\

    Route::post('/plats', [PlatController::class, 'store']);
    Route::get('/plats', [PlatController::class, 'index']);
    Route::get('/categories/{category}/plats', [PlatController::class, 'platsByCategory']);
    Route::get('/plats/{plat}', [PlatController::class, 'show']);
    Route::put('/plats/{plat}', [PlatController::class, 'update']);
    

});