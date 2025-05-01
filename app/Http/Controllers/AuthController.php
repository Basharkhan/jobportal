<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController {
    public function __construct( protected AuthService $authService ) {

    }

    public function register( RegisterRequest $request ): JsonResponse {
        try {
            $user = $this->authService->register( $request->validated() );

            $token = $user->createToken( 'auth_token' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token,
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch ( \Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to register user',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

}
