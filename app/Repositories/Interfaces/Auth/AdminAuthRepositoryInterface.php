<?php
namespace App\Repositories\Interfaces\Auth;
use App\Models\User;

interface AdminAuthRepositoryInterface {
    public function register( array $data ): User;

    public function findByEmail( string $email ): ? User;

    public function validateCredentials( User $user, string $password ): bool;
}