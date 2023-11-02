<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SquareController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Rotas de autenticação de usuário
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
});

//Rotas básicas das quadras
Route::get('squares', [SquareController::class, 'index']);
Route::get('squares/{id}/export', [SquareController::class, 'export']);
Route::get('squares/{id}', [SquareController::class, 'show']);

//Proteção com middleware
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::post('squares', [SquareController::class, 'store']);
    Route::put('squares/{id}', [SquareController::class, 'update']);
    Route::delete('squares/{id}', [SquareController::class, 'destroy']);
});
