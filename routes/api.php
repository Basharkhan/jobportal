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
        Route::get('/employers', [UserController::class, 'getAllEmployers']); 
        Route::get('/employers/{id}', [UserController::class, 'getEmployerById']);       
        Route::patch('/employers/{id}/status', [UserController::class, 'changeEmployerStatus']);
        
        // job seekers
        Route::get('/job-seekers', [UserController::class, 'getAllJobSeekers']);
        Route::get('/job-seekers/{id}', [UserController::class, 'getJobSeekerById']);
        Route::patch('/job-seekers/{id}/status', [UserController::class, 'changeJobSeekerStatus']);

        // all users (admins, employers, job seekers)
        Route::get('/users/email/{email}', [UserController::class, 'getUserByEmail']);  
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'getUserById']);      
        Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
        
        // didn't test
        // applications
        Route::get('/applications/{id}', [ApplicationController::class, 'findApplicationForAdmin']);  
        Route::get('/applications/by-job/{jobId}', [ApplicationController::class, 'getApplicationsByJobForAdmin']);    
        Route::delete('/application/{id}', [ApplicationController::class, 'deleteApplication']);        
        
        // jobs
        Route::delete('/jobs/{id}', [JobPostingController::class, 'deleteJob']);   
        Route::get('/jobs', [JobPostingController::class, 'getAllJobs']);    
        Route::get('/jobs/{id}', [JobPostingController::class, 'show']);   
        Route::patch('/jobs/{id}/status', [JobPostingController::class, 'changeApprovalStatus']);               
    });
});

// employer routes
Route::prefix('employer')->group(function () {
    // auth
    Route::post('/register', [UserAuthController::class, 'registerEmployer']);
    Route::post('/login', [UserAuthController::class, 'loginEmployer']);

    // 👇 Secure logout with middleware group
    Route::middleware('employer')->group(function () {
        // auth
        Route::post('/logout', [UserAuthController::class, 'logoutEmployer']);    

        // didn't test
        Route::get('/application/{id}', [ApplicationController::class, 'findApplicationForEmployer']);     
        Route::get('/applications/by-job/{id}', [ApplicationController::class, 'getApplicationsByJobForEmployer']);    

        // job posting
        Route::prefix('jobs')->group(function () {
            Route::post('/', [JobPostingController::class, 'store']); 
            Route::get('/', [JobPostingController::class, 'index']);  
            Route::get('/search', [JobPostingController::class, 'search']);    
            Route::get('/{id}', [JobPostingController::class, 'getJobByIdForEmployer']);                       
            Route::put('/{id}', [JobPostingController::class, 'update']);                         
        });
    });
});

// both admin and employer routes
Route::middleware(['auth:sanctum', 'role:admin|employer'])->group(function () {
    Route::patch('/jobs/{id}/status', [JobPostingController::class, 'changeJobStatus']);     
});

// job seeker routes
Route::prefix('job-seeker')->group(function () {
    // auth
    Route::post('/register', [UserAuthController::class, 'registerJobSeeker']);
    Route::post('/login', [UserAuthController::class, 'loginJobSeeker']);

    // Protected routes (require job_seeker role)
    Route::middleware('job_seeker')->group(function () {
        // auth
        Route::post('/logout', [UserAuthController::class, 'logoutUser']);
        
        // Application routes
        Route::prefix('applications')->group(function () {
            Route::post('/', [ApplicationController::class, 'store']);
            Route::get('/', [ApplicationController::class, 'index']);            
        });        
    });
});
