<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface {
    public function register( array $data ): User {
        $user  = User::create( [
            'name' => $data[ 'name' ],
            'email' => $data[ 'email' ],
            'password' => Hash::make( $data[ 'password' ] ),
        ] );

        $user->assignRole( $data[ 'role' ] ?? 'job_seeker' );
        return $user;
    }
}