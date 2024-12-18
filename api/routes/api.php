<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('user.get');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');


    Route::group(['prefix' => 'accounts'], function () {
        Route::post('/{account}/deposit', [AccountsController::class, 'deposit'])->name('accounts.deposit');
    });
});

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
