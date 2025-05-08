<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserAuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserAuthRepository implements UserAuthRepositoryInterface {
    public function registerAdmin( array $data ): ?User {
        return DB::transaction( function () use ( $data ) {
            $admin = User::create($data);
            $admin->assignRole('super_admin');

            return $admin;
        } );
    }
}