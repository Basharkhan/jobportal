<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\AdminAuthRequest;
use App\Services\Auth\AdminAuthService;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class AdminAuthController {
    public function __construct( protected AdminAuthService $adminAuthService ) {

    }

    public function registerAdmin( AdminAuthRequest $adminAuthRequest ) {
        try {
            $admin = $this->adminAuthService->register( $adminAuthRequest->validated() );
            $token = $admin->createToken( 'admin_token' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'Admin registered successfully',
                'data' => $admin,
                'token' => $token
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Throwable $e ) {
            Log::error( 'Admin registration failed: ' . $e->getMessage() );
            throw new HttpException( 500, 'Registration service unavailable' );
        }
    }
}
