<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Requests\UserLoginReuqest;
use App\Services\UserAuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class UserAuthController {
    public function __construct( protected UserAuthService $userAuthService ) {

    }

    // public function registerAdmin( AdminRegistrationRequest $adminRegistrationRequest ) {
    //     try {
    //         $admin = $this->userAuthService->registerAdmin( $adminRegistrationRequest->validated() );
    //         $token = $admin->createToken( 'admin_token' )->plainTextToken;

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'Admin registered successfully',
    //             'data' => $admin,
    //             'token' => $token
    //         ], Response::HTTP_CREATED );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( Exception $e ) {
    //         Log::error( 'Admin login error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Login failed'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR );
    //     }
    // }

    // public function loginAdmin(UserLoginReuqest $userLoginReuqest) {
    //     try {
    //         $token = $this->userAuthService->login(
    //             $userLoginReuqest->input( 'email' ),
    //             $userLoginReuqest->input( 'password' ),
    //             'admin'
    //         );

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'Admin logged in successfully',
    //             'token' => $token,
    //         ], Response::HTTP_OK );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( Exception $e ) {
    //         Log::error( 'Admin login error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Login failed'
    //         ],Response::HTTP_INTERNAL_SERVER_ERROR );
    //     }
    // }

    // public function loginEmployer(UserLoginReuqest $userLoginReuqest) {
    //     try {
    //         $token = $this->userAuthService->login(
    //             $userLoginReuqest->input( 'email' ),
    //             $userLoginReuqest->input( 'password' ),
    //             'employer'
    //         );

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'Employer logged in successfully',
    //             'token' => $token,
    //         ], Response::HTTP_OK );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( Exception $e ) {
    //         Log::error( 'Employer login error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Login failed'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR );
    //     }
    // }

    // public function loginUser(UserLoginReuqest $userLoginReuqest) {
    //     try {
    //         $token = $this->userAuthService->login(
    //             $userLoginReuqest->input( 'email' ),
    //             $userLoginReuqest->input( 'password' ),
    //             'job_seeker'
    //         );

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'User logged in successfully',
    //             'token' => $token,
    //         ], Response::HTTP_OK );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( Exception $e ) {
    //         Log::error( 'User login error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Login failed'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR );
    //     }
    // }

    // public function logout() {
    //     try {
    //         $user = auth()->user();
    //         $this->userAuthService->logout( $user, true );

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'User logged out successfully'
    //         ], Response::HTTP_OK );
    //     } catch ( Exception $e ) {
    //         Log::error( 'User logout error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Logout failed'
    //         ], 500 );
    //     }
    // }
}
