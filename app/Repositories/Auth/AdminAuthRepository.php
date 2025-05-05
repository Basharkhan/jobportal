<?php
namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Interfaces\AdminAuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AdminAuthRepository implements AdminAuthRepositoryInterface {
    public function register( array $data ): User {
        return DB::transaction( function () use ( $data ) {
            $admin = User::create($data);
            $admin->assignRole('super_admin');

            return $admin;
        } );
    }

    public function findByEmail( string $email ): ? User {
        return User::role("super_admin")->where( 'email', $email )->first();
    }    
}