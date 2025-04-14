<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\MoodEntryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/verify-code', 'verifyResetCode');
    Route::post('/reset-password', 'resetPassword');
    Route::post('/resend-code', 'resendCode');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);

    Route::apiResource('posts.comments', CommentController::class)->shallow();

    Route::apiResource('mood-entries', MoodEntryController::class);

    Route::apiResource('reactions', ReactionController::class);

    Route::apiResource('blogs', BlogController::class);

    Route::apiResource('tests', TestController::class);

    Route::controller(TestController::class)->group(function () {
        Route::get('tests/{id}/questions', 'getQuestions');
        Route::post('tests/{id}/score', 'calculateScore');
    });
});
