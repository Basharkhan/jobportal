<?php
namespace App\Services;

use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplicationService {
    public function __construct(protected ApplicationRepository $applicationRepository) {
        
    }

    public function applyToJob(array $data): ?Application {
        return $this->applicationRepository->applyToJob($data);
    }    

    public function getApplicationsByUser(int $userId, int $perPage=10) {
        return $this->applicationRepository->getApplicationsByUser($userId, $perPage);
    }


    public function findApplication(int $applicationId): ?Application {
        $application =  $this->applicationRepository->findApplication($applicationId);
        
        if (!$application) {
            throw new NotFoundHttpException('Application not found');
        }        

        return $application;
    }    

    public function findApplicationForAdmin(int $applicationId): ?Application {        
        $user = auth()->user();

        if(!$user->isAdmin()) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        $application =  $this->findApplication($applicationId); 
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