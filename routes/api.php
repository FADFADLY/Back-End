<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\ChatBotController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\MoodEntryController;

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

    Route::post('tests/{id}', [TestController::class, 'calculateScore']);

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'viewProfile');
        Route::put('/name', 'updateName');
        Route::put('/email', 'updateEmail');
        Route::put('/avatar', 'updateAvatar');
        Route::put('/bio', 'updateBio');

        Route::get('/posts', 'userPosts');
    });

    Route::apiResource('books', BookController::class)->only(['index', 'show']);

    Route::post('/chatbot', [ChatbotController::class, 'sendToChatbot']);
});
