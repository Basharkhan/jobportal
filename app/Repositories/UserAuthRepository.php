<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserAuthRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAuthRepository implements UserAuthRepositoryInterface {
    public function registerAdmin( array $data ): ?User {
        return DB::transaction( function () use ( $data ) {
            $admin = User::create($data);
            $admin->assignRole('admin');

            return $admin;
        } );
    }

    public function findByEmail( string $email ): ? User {
        return User::role("admin")->where( 'email', $email )->first();
    }    

    public function validateCredentials( User $user, string $password ): bool {
        return Hash::check($password, $user->password);
    }

    public function revokeAuthTokens( User $user, bool $revokeAll = false ): void {
        if ( $revokeAll ) {
            $user->tokens()->delete();
        } else {
            $user->currentAccessToken()->delete();
        }
    }
}