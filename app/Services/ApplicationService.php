<?php
namespace App\Services;

use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

class ApplicationService {
    public function __construct(protected ApplicationRepository $applicationRepository) {
        
    }

   public function applyToJob(array $data, ?UploadedFile $resumeFile): ?Application {
        $user = auth()->user();
        $jobPostingId = $data['job_posting_id'];
        $existingApplication = $this->applicationRepository->getApplicationByJobSeekerIdAndJobPostingId($user->id, $jobPostingId);
        
        if ($existingApplication && $existingApplication->job_posting_id == $data['job_posting_id']) {
            throw new AccessDeniedHttpException('You have already applied for this job');
        }

        if ($resumeFile) {
            $data['resume'] = $this->uploadResume($resumeFile);
        }

        $data['user_id'] = $user->id;

        return $this->applicationRepository->applyToJob($data);
    }   

    public function uploadResume(UploadedFile $file): string {
        $fileName = time() . '_' . Str::slug($file->getClientOriginalName());
        $file->storeAs('resumes', $fileName, 'public'); 
        return $fileName;
    }

    public function getApplicationsByJobSeeker(int $userId, int $perPage=10) {
        return $this->applicationRepository->getApplicationsByJobSeeker($userId, $perPage);
    }


    public function findApplication(int $applicationId): ?Application {
        $application =  $this->applicationRepository->findApplication($applicationId);
        
        if (!$application) {
            throw new NotFoundHttpException('Application not found');
        }        

        return $application;
    }        

     public function findApplicationForEmployer(int $applicationId): ?Application {
        $user = auth()->user();

        if(!$user->isEmployer()) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        $application =  $this->findApplication($applicationId);          
        return $application;
    }

    public function findApplicationForJobSeeker(int $applicationId): ?Application {
        $user = auth()->user();

        if(!$user->isJobSeeker() ) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        $application =  $this->findApplication($applicationId);          
        return $application;
    }    

    public function getApplicationsByJobForAdmin(int $jobId, int $perPage=10) {
        $user = auth()->user();

        if(!$user->isAdmin()) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }
        
        $applications = $this->applicationRepository->getApplicationsByJob($jobId, $perPage);
        
        if ($applications->total() == 0) {
            throw new NotFoundHttpException('Applications not found');
        }
        
        return $applications;
    }

    public function getApplicationsByJobForEmployer(int $jobId, int $perPage=10) {
        $user = auth()->user();

        if(!$user->isEmployer()) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }
        
        $applications = $this->applicationRepository->getApplicationsByJob($jobId, $perPage);

        if ($applications->total() == 0) {
            throw new NotFoundHttpException('Applications not found');
        }

        return $applications;
    }

    public function deleteApplication(int $applicationId): bool {
        $user = auth()->user();

        if(!$user->isAdmin()) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        return $this->applicationRepository->deleteApplication($applicationId);
    }
}