<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\socialAuthController;
use App\Http\Controllers\UsersContoller;
use Illuminate\Http\Request;
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


// Public Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/passowrd', 'sendResetLink');

    Route::post('/reset-password', 'reset');
});

Route::get('/login-google', [socialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/callback', [socialAuthController::class, 'handleCallback']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    // Users
    Route::controller(UsersContoller::class)->group(function () {
        Route::get('/users', 'GetUsers');
        Route::get('/user', 'authUser');
        Route::get('/user/{id}', 'getUser');
        Route::get('/user/edit/{id}', 'editUser');
        Route::delete('/user/{id}', 'destroy');
    });

    // Auth
    Route::get('/logout', [AuthController::class, 'logout']);
});
