<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\EmployerController;
use App\Http\Controllers\Api\JobPostingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// admin routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'loginAdmin']);

    // 👇 Secure with admin group
    Route::middleware('admin')->group(function () {
        Route::post('/register', [AdminController::class, 'registerAdmin']);
        Route::post('/logout', [AdminController::class, 'logoutAdmin']);
        Route::get('/application/{id}', [ApplicationController::class, 'findApplicationForAdmin']);  
        Route::get('/applications/by-job/{id}', [ApplicationController::class, 'getApplicationsByJobForAdmin']);    
        Route::delete('/application/{id}', [ApplicationController::class, 'deleteApplication']);        
        Route::delete('/jobs/{id}', [JobPostingController::class, 'deleteJob']);   
        Route::get('/jobs', [JobPostingController::class, 'allJobs']);        
        Route::get('/employers', [EmployerController::class, 'getAllEmployers']);        
    });
});

// employer routes
Route::prefix('employer')->group(function () {
    Route::post('/register', [EmployerController::class, 'registerEmployer']);
    Route::post('/login', [EmployerController::class, 'loginEmployer']);

    // 👇 Secure logout with middleware group
    Route::middleware('employer')->group(function () {
        Route::post('/logout', [EmployerController::class, 'logoutEmployer']);    
        Route::get('/application/{id}', [ApplicationController::class, 'findApplicationForEmployer']);     
        Route::get('/applications/by-job/{id}', [ApplicationController::class, 'getApplicationsByJobForEmployer']);    

        Route::prefix('jobs')->group(function () {
            Route::post('/', [JobPostingController::class, 'store']); 
            Route::get('/', [JobPostingController::class, 'index']);  
            Route::get('/search', [JobPostingController::class, 'search']); 
            Route::get('/{id}', [JobPostingController::class, 'show']);   
            Route::put('/{id}', [JobPostingController::class, 'update']);                         
        });
    });
});

// both admin and employer routes
Route::middleware(['auth:sanctum', 'role:admin|employer'])->group(function () {
    Route::patch('/jobs/{id}/status', [JobPostingController::class, 'changeJobStatus']);
});

// user routes
Route::prefix('user')->group(function () {
    // Public routes (no auth)
    Route::post('/register', [UserController::class, 'registerUser']);
    Route::post('/login', [UserController::class, 'loginUser']);

    // Protected routes (require job_seeker role)
    Route::middleware('job_seeker')->group(function () {
        // Auth routes
        Route::post('/logout', [UserController::class, 'logoutUser']);

        // Application routes
        Route::prefix('applications')->group(function () {
            Route::post('/', [ApplicationController::class, 'store']);
            Route::get('/', [ApplicationController::class, 'index']);
            Route::get('/{id}', [ApplicationController::class, 'findApplicationForJobSeeker']);
        });

        // Future job seeker-specific routes can go here
        // Route::prefix('profile')->group(...);
    });
});
