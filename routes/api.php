<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\JobPostingController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// admin routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [UserAuthController::class, 'loginAdmin']);

    // 👇 Secure with admin group
    Route::middleware('admin')->group(function () {
        // auth
        Route::post('/register', [UserAuthController::class, 'registerAdmin']);
        Route::post('/logout', [UserAuthController::class, 'logoutAdmin']);

        // admins
        Route::get('/admins', [UserController::class, 'getAllAdmins']);        

        // employers
        Route::get('/employers', [UserController::class, 'getEmployers']);        

        // job seekers
        Route::get('/job-seekers', [UserController::class, 'getAllJobSeekers']);
        
        // all users
        // Route::get('/users/email/{email}', [UserController::class, 'getUserByEmail']);  
        Route::get('/users/{id}', [UserController::class, 'getUserById']);      
        Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
        
        // applications
        Route::get('/applications/{id}', [ApplicationController::class, 'findApplicationForAdmin']);  
        Route::get('/applications/by-job/{id}', [ApplicationController::class, 'getApplicationsByJobForAdmin']);    
        Route::delete('/application/{id}', [ApplicationController::class, 'deleteApplication']);        
        
        // jobs
        Route::delete('/jobs/{id}', [JobPostingController::class, 'deleteJob']);   
        Route::get('/jobs', [JobPostingController::class, 'allJobs']);                      
    });
});

// employer routes
Route::prefix('employer')->group(function () {
    Route::post('/register', [UserAuthController::class, 'registerEmployer']);
    Route::post('/login', [UserAuthController::class, 'loginEmployer']);

    // 👇 Secure logout with middleware group
    Route::middleware('employer')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logoutEmployer']);    
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
    Route::post('/register', [UserAuthController::class, 'registerUser']);
    Route::post('/login', [UserAuthController::class, 'loginUser']);

    // Protected routes (require job_seeker role)
    Route::middleware('job_seeker')->group(function () {
        // Auth routes
        Route::post('/logout', [UserAuthController::class, 'logoutUser']);

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
