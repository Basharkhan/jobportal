<?php
namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmployerRepositoryInterface {
    public function getAll(int $perPage = 10): LengthAwarePaginator;
}