<?php
namespace App\Repositories\Interfaces;
use App\Models\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface {
    public function applyToJob(array $data): ?Application;

    public function getApplicationsByUser(int $userId, int $perPage=10): LengthAwarePaginator;

    public function findApplication(int $applicationId): ?Application;
    
    // public function getApplicationsByJob(int $jobId): LengthAwarePaginator;
    

    // public function updateStatus(int $applicationId, string $status): bool;

    // public function deleteApplication(int $applicationId): bool;
}