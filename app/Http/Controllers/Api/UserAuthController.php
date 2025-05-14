<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdminRegistrationRequest;
use App\Services\UserAuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class UserAuthController {
    public function __construct( protected UserAuthService $userAuthService ) {

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
            ], 500 );
        }
    }
}
