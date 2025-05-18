<?php
namespace App\Repositories\Interfaces;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface EmployerRepositoryInterface {
    public function createJob(array $data): ?JobPosting;

    public function getJobsByEmployerId(int $employerId): Collection;

    public function findJobById(int $jobId): ?JobPosting;

    // public function updateJob(int $jobId, array $data): bool;

    // public function deleteJob(int $jobId): bool;

    // public function changeJobStatus(int $jobId, string $status): bool;
}