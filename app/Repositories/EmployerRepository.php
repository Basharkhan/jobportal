<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\EmployerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployerRepository implements EmployerRepositoryInterface {
    public function getAll(int $perPage = 10): LengthAwarePaginator {
        return User::role('employer')->latest()->paginate($perPage);
    }
}