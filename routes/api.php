<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/userDetails', [UserController::class, 'userDetails'])->name('userDetails');

    Route::post('/index', [PostController::class, 'index'])->name('index');
    Route::post('/store', [PostController::class, 'store'])->name('store');
});
