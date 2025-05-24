<?php 
namespace App\Repositories;

use App\Models\JobPosting;
use App\Repositories\Interfaces\JobPostingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class JobPostingRepository implements JobPostingRepositoryInterface {
    public function createJob(array $data): ?JobPosting {
        return DB::transaction(function () use ($data) {
            $job = JobPosting::create([
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'job_type' => $data['job_type'],
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'salary_currency' => $data['salary_currency'] ?? 'USD',
                'experience_level' => $data['experience_level'],
                'education_level' => $data['education_level'],
                'application_deadline' => $data['application_deadline'],
                'remote' => $data['remote'] ?? false,
                'benefits' => $data['benefits'] ?? null,
                'requirements' => $data['requirements'] ?? null,
                'responsibilities' => $data['responsibilities'] ?? null,
            ]);

            return $job;
        });        
    }

    public function getJobsByEmployerId(int $employerId, int $perPage): LengthAwarePaginator {
        return JobPosting::where('user_id', $employerId)->latest()
            ->paginate($perPage);
    }

    public function getAllJobs(int $perPage=10): LengthAwarePaginator {
        return JobPosting::latest()->paginate($perPage);
    }

    public function findJobById(int $jobId): ?JobPosting{
        return JobPosting::find($jobId);
    }

    public function updateJob(int $jobId, array $data): ?JobPosting {
        return DB::transaction(function () use ($jobId, $data) {
            $job = JobPosting::find($jobId);
            if (!$job) {
                return null;
            }

            $job->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'job_type' => $data['job_type'],
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'salary_currency' => $data['salary_currency'] ?? 'USD',
                'experience_level' => $data['experience_level'],
                'education_level' => $data['education_level'],
                'application_deadline' => $data['application_deadline'],
                'remote' => $data['remote'] ?? false,
                'benefits' => $data['benefits'] ?? null,
                'requirements' => $data['requirements'] ?? null,
                'responsibilities' => $data['responsibilities'] ?? null,
            ]);

            return $job;
        });        
    }

    public function deleteJob(int $jobId): bool {
        return DB::transaction(function () use ($jobId) {
            $job = JobPosting::find($jobId);
            if (!$job) {
                return false;
            }

            return $job->delete();
        });
    }

    public function changeJobStatus(int $jobId, string $status): bool
    {
        return DB::transaction(function () use ($jobId, $status) {
            
            $job = JobPosting::find($jobId);
            if (!$job) {
                return false;
            }

            $job->status = $status;
            return $job->save();            
        });
    }

    public function searchEmployerJobs(int $employerId, array $filters, int $perPage=10): LengthAwarePaginator {
        $query = JobPosting::query()->where('user_id', $employerId);

        if(!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['job_type'])) {
            $query->where('job_type', $filters['job_type']);
        }

        return $query->latest()->paginate($perPage);
    }
}