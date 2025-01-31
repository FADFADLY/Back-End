<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\MoodEntryController;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;

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

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::post('/oauth/clients', [ClientController::class, 'store']);
Route::put('/oauth/clients/{client_id}', [ClientController::class, 'update']);
Route::delete('/oauth/clients/{client_id}', [ClientController::class, 'destroy']);
Route::get('/oauth/scopes', [ScopeController::class, 'all']);
Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'forgotPassword');
    Route::post('/password/reset', 'resetPassword')->name('password.reset');
    Route::middleware('auth:api')->post('/logout', 'logout');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('post', PostController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('post.comment', CommentController::class)->shallow();
    Route::apiResource('mood-entries', MoodEntryController::class);
    Route::apiResource('reaction', ReactionController::class);
    Route::apiResource('blog', BlogController::class);

    Route::controller(TestController::class)->group(function () {
        Route::post('calculateScore/{id}', 'calculateScore');
    });

    Route::apiResource('test', TestController::class);
<<<<<<< HEAD

    Route::ApiResource('reaction', ReactionController::class);
    Route::resource('blog', BlogController::class);
=======
>>>>>>> aaed4497c724699c7c685fd4aa728e05723b77c8
});
