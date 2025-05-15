<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmployerRegisterRequest;
use App\Http\Requests\UserLoginReuqest;
use App\Services\EmployerService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class EmployerController {
    public function __construct( protected EmployerService $employerService ) {

    }

    // public function registerEmployer( EmployerRegisterRequest $employerRegisterRequest ) {
    //     try {
    //         $employer = $this->employerService->registerEmployer( $employerRegisterRequest->validated() );
    //         $token = $employer->createToken( 'employer' )->plainTextToken;

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'Employer registered successfully',
    //             'data' => $employer,
    //             'token' => $token,
    //         ], Response::HTTP_CREATED );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( Exception $e ) {
    //         Log::error( 'Employer registration error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Registration failed'
    //         ], 500 );
    //     }
    // }

    // public function loginEmployer( UserLoginReuqest $userLoginReuqest ) {
    //     try {
    //         $token = $this->employerService->loginEmployer(
    //             $userLoginReuqest->input( 'email' ),
    //             $userLoginReuqest->input( 'password' )
    //         );

    //         return response()->json( [
    //             'success' => true,
    //             'message' => 'Employer logged in successfully',
    //             'token' => $token,
    //         ], Response::HTTP_OK );
    //     } catch ( ValidationException $e ) {
    //         throw $e;
    //     } catch ( AuthenticationException $e ) {
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401 );

    //     } catch ( Exception $e ) {
    //         Log::error( 'Employer login error: ' . $e->getMessage() );
    //         return response()->json( [
    //             'success' => false,
    //             'message' => 'Login failed'
    //         ], 500 );
    //     }
    // }
}
