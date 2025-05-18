<?php
namespace App\Repositories\Interfaces;

use App\Models\JobPosting;
use App\Models\User;

interface EmployerRepositoryInterface {
    public function createJob(array $data): ?JobPosting;
}