<?php

use App\Http\Controllers\Api\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'registerAdmin']);        
});