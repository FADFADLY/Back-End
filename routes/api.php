<?php

use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\NotificationController;
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
use App\Http\Controllers\Api\TimerController;
use App\Http\Controllers\Api\PodcastController;

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
    Route::post('posts/{post}/vote', [PostController::class, 'vote']);

    Route::apiResource('posts.comments', CommentController::class)->shallow();

    Route::apiResource('mood-entries', MoodEntryController::class);

    Route::apiResource('reactions', ReactionController::class);
    Route::get('liked-items', [ReactionController::class, 'likedItems']);


    Route::apiResource('blogs', BlogController::class);

    Route::apiResource('tests', TestController::class);

    Route::post('tests/{id}', [TestController::class, 'calculateScore']);

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'viewProfile');
        Route::put('/name', 'updateName');
        Route::put('/email', 'updateEmail');
        Route::post('/avatar', 'updateAvatar');
        Route::put('/bio', 'updateBio');
        Route::get('/posts', 'userPosts');
    });

    Route::apiResource('books', BookController::class)->only(['index', 'show']);

    Route::apiresource('habits', HabitController::class)->only(['index', 'store']);

    Route::controller(TimerController::class)->prefix('timers')->group(function () {
        Route::post('start', 'startOrResume');
        Route::post('{id}/pause', 'pause');
        Route::post('{id}/stop', 'stop');
        Route::get('{id}/duration', 'duration');
    });

    Route::post('/chatbot/send', [ChatbotController::class, 'sendToChatbot']);
    Route::get('/chatbot/chats', [ChatbotController::class, 'getChats']);
    Route::get('/chatbot/chats/{id}', [ChatbotController::class, 'getChatMessages']);
    Route::delete('/chatbot/chats/{id}', [ChatbotController::class, 'deleteChat']);

    Route::prefix('podcasts')->group(function () {
        Route::get('/', [PodcastController::class, 'index']);
        Route::get('{id}', [PodcastController::class, 'show']);
    });

    Route::prefix('episodes')->group(function () {
        Route::get('{id}', [PodcastController::class, 'episode']);
    });


    Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
        Route::get('/', 'index');
        Route::post('/{id}/read', 'read');
    });


});
