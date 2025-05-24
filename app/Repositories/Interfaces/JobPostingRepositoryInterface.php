<?php
namespace App\Repositories\Interfaces;
use App\Models\JobPosting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface JobPostingRepositoryInterface {
    public function createJob(array $data): ?JobPosting;

    public function getJobsByEmployerId(int $employerId, int $perPage): LengthAwarePaginator;

    public function getAllJobs(int $perPage=10): LengthAwarePaginator;

    public function findJobById(int $jobId): ?JobPosting;

    public function updateJob(int $jobId, array $data): ?JobPosting;

    public function deleteJob(int $jobId): bool;

    public function changeJobStatus(int $jobId, string $status): bool;

    public function searchEmployerJobs(int $employerId, array $filters, int $perPage=10): LengthAwarePaginator;
}