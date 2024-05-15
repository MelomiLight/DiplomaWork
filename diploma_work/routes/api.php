<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RunningSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('sanctum')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'changePassword']);
});

//protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('/run')->group(function (){
        Route::post('/session/store', [RunningSessionController::class, 'store']);
        Route::get('/session/index', [RunningSessionController::class, 'index']);
    });
    Route::prefix('/user')->group(function (){
        Route::get('/index', [UserController::class, 'index']);
        Route::get('/show/{user}', [UserController::class, 'show']);
        Route::patch('/update', [UserController::class, 'update']);
    });
});

