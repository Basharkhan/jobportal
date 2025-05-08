<?php

namespace App\Http\Controllers\Api;

use App\Services\UserAuthService;
use Illuminate\Http\Request;

class UserAuthController {
    public function __construct( protected UserAuthService $userAuthService ) {

    }
}
