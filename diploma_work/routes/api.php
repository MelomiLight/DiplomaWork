<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckChallengesController;
use App\Http\Controllers\RunningSessionController;
use App\Http\Controllers\UserChallengeController;
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

    Route::prefix('/run')->group(function () {
        Route::post('/session/store', [RunningSessionController::class, 'store']);
        Route::get('/session/index', [RunningSessionController::class, 'index']);
        Route::delete('/session/delete/{runningSession}', [RunningSessionController::class, 'destroy']);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::get('/show/{user}', [UserController::class, 'show']);
        Route::patch('/update', [UserController::class, 'update']);
        Route::delete('/delete/{user}', [UserController::class, 'destroy']);

        Route::prefix('/challenges')->group(function () {
            Route::post('/store', [UserChallengeController::class, 'store']);
            Route::get('/show/{user}', [UserChallengeController::class, 'show']);
            Route::delete('/delete/{user}', [UserChallengeController::class, 'destroy']);
        });

    });

    Route::prefix('/challenges')->group(function () {
        Route::get('/check', [CheckChallengesController::class, 'setChallenge']);
        Route::post('/store', [CheckChallengesController::class, 'store']);
        Route::get('/index', [CheckChallengesController::class, 'index']);
        Route::delete('/delete/{challenge}', [CheckChallengesController::class, 'destroy']);
    });

});

