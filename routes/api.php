<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TaskController;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('jwt.auth');
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });

        Route::get('tasks/all', [TaskController::class, 'all']);
        Route::apiResource('tasks', TaskController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::get('news/articles', [NewsController::class, 'getArticles']);
    });
});
