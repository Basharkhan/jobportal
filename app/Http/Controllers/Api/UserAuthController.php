<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Requests\EmployerRegisterRequest;
use App\Http\Requests\UserLoginReuqest;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\UserAuthService;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserAuthController {
    public function __construct(protected UserAuthService $userAuthService) {
        
    }

    public function registerAdmin( AdminRegistrationRequest $adminRegistrationRequest ) {
        try {
            $admin = $this->userAuthService->registerAdmin( $adminRegistrationRequest->validated() );
            $token = $admin->createToken( 'admin_token' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'Admin registered successfully',
                'data' => $admin,
                'token' => $token
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Exception $e ) {
            Log::error( 'Admin login error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Login failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function loginAdmin( UserLoginReuqest $userLoginReuqest ) {
        try {
            $token = $this->userAuthService->login(
                $userLoginReuqest->input( 'email' ),
                $userLoginReuqest->input( 'password' ),
                'admin'
            );

            return response()->json( [
                'success' => true,
                'message' => 'Admin logged in successfully',
                'token' => $token,
            ], Response::HTTP_OK );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( AuthenticationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED );
        } catch ( Exception $e ) {
            Log::error( 'Admin login error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Login failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function logoutAdmin() {
        try {
            $user = auth()->user();
            $this->userAuthService->logout( $user, true );

            return response()->json( [
                'success' => true,
                'message' => 'Admin logged out successfully'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Admin logout error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Logout failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
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

    // public function getAllEmployers( Request $request ) {        
    //     try {
    //         $perPage = $request->input( 'per_page', 10 );
    //         $employers = $this->employerService->getAllEmployers( $perPage );

    //         return response()->json( [
    //             'success' => true,
    //             'data' => $employers,
    //         ], Response::HTTP_OK );
    //     } catch(UnauthorizedHttpException $e) {
    //         return response()->json( [
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], Response::HTTP_UNAUTHORIZED );
    //     } catch ( Exception $e ) {
    //         Log::error( 'Get all employers error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Failed to retrieve employers'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR );
    //     }
    // }

    public function registerUser( UserRegistrationRequest $userRegistrationRequest ) {
        try {
            $admin = $this->userAuthService->registerUser( $userRegistrationRequest->validated() );
            $token = $admin->createToken( 'user_token' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $admin,
                'token' => $token
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Exception $e ) {
            Log::error( 'User registration error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Registration failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function loginUser(UserLoginReuqest $userLoginReuqest) {
        try {
            $token = $this->userAuthService->login(
                $userLoginReuqest->input( 'email' ),
                $userLoginReuqest->input( 'password' ),
                'job_seeker'
            );

            return response()->json( [
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $token,
            ], Response::HTTP_OK );
        } catch(AuthenticationException $e) {            
            return response()->json( [
                'success' => false,
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Exception $e ) {
            Log::error( 'Admin login error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Login failed'
            ],Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function logoutUser() {
        try {
            $user = auth()->user();
            $this->userAuthService->logout( $user, true );

            return response()->json( [
                'success' => true,
                'message' => 'User logged out successfully'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'User logout error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Logout failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }
}
