<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('api');
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });

        Route::get('tasks/all', [TaskController::class, 'all']);
        Route::apiResource('tasks', TaskController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });
});
