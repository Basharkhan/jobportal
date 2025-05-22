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

    public function getApplicationsByUser(int $userId, int $perPage=10): LengthAwarePaginator {
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

    public function getApplicationsByJob(int $jobId, int $perPage=10): LengthAwarePaginator {
        return Application::where('job_posting_id', $jobId)
            ->with(['jobPosting', 'seeker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function deleteApplication(int $applicationId): bool {
        $application = $this->findApplication($applicationId);
        if ($application) {
            return $application->delete();
        }
        return false;
    }
}