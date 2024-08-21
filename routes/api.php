<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('user/logout', [LogoutController::class, 'logout'])->name('user.logout');
    Route::apiResource('product', ProductController::class);
    Route::apiResource('category', CategoryController::class);
    Route::get('get-user', [UserController::class, 'getUser'])->name('get.user');
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('user/register', [RegisterController::class, 'register'])->name('user.register');
    Route::post('user/login', [LoginController::class, 'login'])->name('user.login');
});
