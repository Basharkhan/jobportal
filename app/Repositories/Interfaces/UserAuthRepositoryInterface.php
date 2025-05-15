<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserAuthRepositoryInterface {
    public function registerAdmin( array $data ): ?User;
    
    public function findByEmail( string $email ): ? User;

    public function validateCredentials( User $user, string $password ): bool;

    public function revokeAuthTokens( User $user, bool $revokeAll = false ): void;    

    public function registerEmployer( array $data ): ?User;
}