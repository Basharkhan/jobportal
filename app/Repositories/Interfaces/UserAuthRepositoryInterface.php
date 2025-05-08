<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserAuthRepositoryInterface {
    public function registerAdmin( array $data ): ?User;
}