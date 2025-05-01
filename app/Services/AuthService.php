<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthService {
    public function __construct( protected AuthRepositoryInterface $authRepositoryInterface ) {

    }

    public function register( array $data ): User {
        return $this->authRepositoryInterface->register( $data );
    }
}