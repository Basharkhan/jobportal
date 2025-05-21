<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmployerRegisterRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\UserLoginReuqest;
use App\Services\EmployerService;
use App\Services\UserAuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class EmployerController {
    public function __construct( protected UserAuthService $userAuthService ) {        

    }

    public function registerEmployer( EmployerRegisterRequest $employerRegisterRequest ) {
        try {
            $employer = $this->userAuthService->registerEmployer( $employerRegisterRequest->validated() );
            $token = $employer->createToken( 'employer' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'Employer registered successfully',
                'data' => $employer,
                'token' => $token,
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Exception $e ) {
            Log::error( 'Employer registration error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Registration failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function loginEmployer( UserLoginReuqest $userLoginReuqest ) {
        try {
            $token = $this->userAuthService->login(
                $userLoginReuqest->input( 'email' ),
                $userLoginReuqest->input( 'password' ),
                'employer'
            );

            return response()->json( [
                'success' => true,
                'message' => 'Employer logged in successfully',
                'token' => $token,
            ], Response::HTTP_OK );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( AuthenticationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401 );

        } catch ( Exception $e ) {
            Log::error( 'Employer login error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Login failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function logoutEmployer() {
        try {
            $user = auth()->user();           
            $this->userAuthService->logout( $user, true );

            return response()->json( [
                'success' => true,
                'message' => 'Employer logged out successfully'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Employer logout error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Logout failed'
            ], 500 );
        }
    }     
}
