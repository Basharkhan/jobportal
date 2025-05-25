<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdminRegistrationRequest;
use App\Http\Requests\UserLoginReuqest;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\UserAuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Auth\AuthenticationException;

class UserController {
    public function __construct( protected UserAuthService $userAuthService ) {

    }

    
}
