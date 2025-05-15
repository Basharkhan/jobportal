<?php
namespace App\Repositories;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository implements AdminRepositoryInterface {
    // public function register( array $data ): User {
    //     return DB::transaction( function () use ( $data ) {
    //         $admin = User::create($data);
    //         $admin->assignRole('super_admin');

    //         return $admin;
    //     } );
    // }

    // public function findByEmail( string $email ): ? User {
    //     return User::role("super_admin")->where( 'email', $email )->first();
    // }    

    // public function validateCredentials( User $user, string $password ): bool {
    //     return Hash::check($password, $user->password);
    // }

    // public function revokeAuthTokens( User $user, bool $revokeAll = false ): void {
    //     if ( $revokeAll ) {
    //         $user->tokens()->delete();
    //     } else {
    //         $user->currentAccessToken()->delete();
    //     }
    // }
}