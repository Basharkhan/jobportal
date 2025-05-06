<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\AdminAuthRequest;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Services\Auth\AdminAuthService;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdminAuthController {
    public function __construct( protected AdminAuthService $adminAuthService ) {

    }

    public function registerAdmin( AdminAuthRequest $adminAuthRequest ) {
        try {
            $admin = $this->adminAuthService->registerAdmin( $adminAuthRequest->validated() );
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
            ], 500 );
        }
    }

    public function loginAdmin( AdminLoginRequest $adminLoginRequest ) {
        try {
            $token = $this->adminAuthService->loginAdmin(
                $adminLoginRequest->input( 'email' ),
                $adminLoginRequest->input( 'password' )
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
            ], 401 );

        } catch ( Exception $e ) {
            Log::error( 'Admin login error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Login failed'
            ], 500 );
        }
    }

    public function logoutAdmin() {
        try {
            $user = auth()->user();
            $this->adminAuthService->logoutAdmin( $user );

            return response()->json( [
                'success' => true,
                'message' => 'Admin logged out successfully'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Admin logout error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Logout failed'
            ], 500 );
        }
    }

    public function forceLogoutAdmin() {
        try {
            $user = auth()->user();
            $this->adminAuthService->logoutAdmin( $user, true );

            return response()->json( [
                'success' => true,
                'message' => 'Admin logged out successfully from all devices'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Admin logout error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Logout failed'
            ], 500 );
        }
    }
}
