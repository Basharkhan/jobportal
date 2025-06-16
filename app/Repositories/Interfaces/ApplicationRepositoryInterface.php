<?php
namespace App\Repositories\Interfaces;
use App\Models\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface {
    public function applyToJob(array $data): ?Application;

    public function getApplicationsByJobSeeker(int $userId, int $perPage=10): LengthAwarePaginator;

    public function findApplication(int $applicationId): ?Application;
    
    public function getApplicationByJobSeekerIdAndJobPostingId(int $jobSeekerId, int $jobPostingId): ?Application;

    public function getApplicationsByJobForAdmin(int $jobId, int $perPage=10): LengthAwarePaginator;
    
    public function getApplicationsByJobForEmployer(int $jobId, int $perPage=10): LengthAwarePaginator;    

    public function deleteApplication(int $applicationId): bool;

    public function getApplications(int $perPage=10): LengthAwarePaginator;
}