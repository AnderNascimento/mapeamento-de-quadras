<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SquareController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//ROTAS DE AUTENTICAÇÃO
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
});

Route::get('squares', [SquareController::class,'index']);
Route::get('squares/export-pdf', [SquareController::class,'export']);
Route::get('squares/{id}', [SquareController::class,'store']);

//Proteção com middleware
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('users',[UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    //Squares
    Route::post('squares',[SquareController::class, 'store']);
    Route::put('squares/{id}', [SquareController::class, 'update']);
    Route::delete('squares/{id}', [SquareController::class, 'destroy']);
});
