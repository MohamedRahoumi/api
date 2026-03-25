<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API Laravel communique parfaitement avec Vue.js !'
    ]);
});

Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/questions', [QuestionController::class, 'store']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    Route::post('/questions/{question}/responses', [ResponseController::class, 'store']);
    Route::delete('/responses/{response}', [ResponseController::class, 'destroy']);

    Route::post('/questions/{question}/like', [LikeController::class, 'toggle']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/questions/{question}/favorite', [FavoriteController::class, 'toggle']);
});
