<?php
namespace App\Services;

use App\Repositories\UserAuthRepository;

class UserAuthService {
    public function __construct( protected UserAuthRepository $userAuthRepository ) {

    }

    public function registerAdmin( array $data ) {
        return $this->userAuthRepository->registerAdmin( [
            'name' => $data[ 'name' ],
            'email' => $data[ 'email' ],
            'password' => $data[ 'password' ],
            'email_verified_at' => now(),
        ] );
    }
}