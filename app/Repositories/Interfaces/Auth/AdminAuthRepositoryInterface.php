<?php
namespace App\Repositories\Interfaces;
use App\Models\User;

interface AdminAuthRepositoryInterface {
    public function register( array $data );

    public function findByEmail( string $email ): ? User;
}