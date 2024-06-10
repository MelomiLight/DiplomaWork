<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckChallengesController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\RunningSessionController;
use App\Http\Controllers\UserChallengeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'changePassword']);

//protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/run')->group(function () {
        Route::post('/session', [RunningSessionController::class, 'store']);
        Route::get('/session', [RunningSessionController::class, 'index']);
        Route::delete('/session/{runningSession}', [RunningSessionController::class, 'destroy']);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'get']);
        Route::get('/index', [UserController::class, 'index']);
        Route::get('/show/{user}', [UserController::class, 'show']);
        Route::patch('/', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'destroy']);

        Route::prefix('/challenges')->group(function () {
            Route::post('/', [UserChallengeController::class, 'store']);
            Route::get('/', [UserChallengeController::class, 'show']);
            Route::delete('/', [UserChallengeController::class, 'destroy']);
        });

    });

    Route::prefix('/challenges')->group(function () {
        Route::get('/check', [CheckChallengesController::class, 'setChallenge']);
        Route::post('/', [CheckChallengesController::class, 'store']);
        Route::get('/', [CheckChallengesController::class, 'index']);
        Route::delete('/{challenge}', [CheckChallengesController::class, 'destroy']);
    });

    Route::get('/leaderboard', [LeaderboardController::class, 'index']);

    Route::get('/user_points', function (Request $request) {
        $user = $request->user();
        return response()->json($user->userPoints);
    });
});

