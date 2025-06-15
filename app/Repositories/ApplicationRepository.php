<?php
namespace App\Repositories;

use App\Models\Application;
use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApplicationRepository implements ApplicationRepositoryInterface {
    public function applyToJob(array $data): ?Application {        
        $application = Application::create([
            'job_posting_id' => $data['job_posting_id'],
            'user_id' => $data['user_id'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume' => $data['resume'] ?? null,
            'status' => 'pending'
        ]);
        return $application;
    }

    public function getApplicationsByJobSeeker(int $userId, int $perPage=10): LengthAwarePaginator {
        return Application::where('user_id', $userId)
            ->with(['jobPosting', 'seeker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findApplication(int $applicationId): ?Application {
        return Application::with(['jobPosting', 'seeker'])
            ->where('id', $applicationId)
            ->first();
    }

    public function getApplicationByJobSeekerIdAndJobPostingId(int $jobSeekerId, int $jobPostingId): ?Application {        
        return Application::where('user_id', $jobSeekerId)->where('job_posting_id', $jobPostingId)
            ->first();                        
    }

    public function getApplicationsByJobForAdmin(int $jobId, int $perPage=10): LengthAwarePaginator {
        return Application::where('job_posting_id', $jobId)
            ->with(['jobPosting', 'seeker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getApplicationsByJobForEmployer(int $jobId, int $perPage=10): LengthAwarePaginator {
        $employerId = auth()->user()->id;
        
        $applications = Application::whereHas('jobPosting', function($query) use ($employerId) {
                $query->where('user_id', $employerId);
            })
            ->where('job_posting_id', $jobId)
            ->with(['jobPosting', 'seeker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return $applications;
    }

    public function deleteApplication(int $applicationId): bool {
        $application = $this->findApplication($applicationId);
        if ($application) {
            return $application->delete();
        }
        return false;
    }

    public function getApplications(int $perPage=10): LengthAwarePaginator {
        return Application::with(['jobPosting', 'seeker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}