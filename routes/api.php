<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\EmployerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// admin routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'loginAdmin']);

    // 👇 Secure with admin group
    Route::middleware('admin')->group(function () {
        Route::post('/register', [AdminController::class, 'registerAdmin']);
        Route::post('/logout', [AdminController::class, 'logoutAdmin']);
    });
});


// employer routes
Route::prefix('employer')->group(function () {
    Route::post('/register', [EmployerController::class, 'registerEmployer']);
    Route::post('/login', [EmployerController::class, 'loginEmployer']);

    // 👇 Secure logout with middleware group
    Route::middleware('employer')->group(function () {
        Route::post('/logout', [EmployerController::class, 'logoutEmployer']);
    });
});


// user routes
Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'registerUser']);
    Route::post('/login', [UserController::class, 'loginUser']);

    Route::middleware('job_seeker')->group(function () {
        Route::post('/logout', [UserController::class, 'logoutUser']);
    });
});
