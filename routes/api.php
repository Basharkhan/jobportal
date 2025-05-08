<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\EmployerController;
use Illuminate\Support\Facades\Route;


// admin routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'loginAdmin']);
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'registerAdmin']);        
    Route::post('/logout', [AdminController::class, 'logoutAdmin']);        
    Route::post('/force-logout', [AdminController::class, 'forceLogoutAdmin']);        
});

// employer routes
Route::prefix('employer')->group(function () {
    Route::post('/register', [EmployerController::class, 'registerEmployer']);
    Route::post('/login', [EmployerController::class, 'loginEmployer']);
});