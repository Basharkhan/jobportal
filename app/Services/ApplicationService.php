<?php
namespace App\Services;

use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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
        
        $user = auth()->user();

        if($user->isJobSeeker() && $user->id !== $application->user_id) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        if($user->isEmployer() && $user->id !== $application->jobPosting->user_id) {
            throw new AccessDeniedHttpException('You are not authorized to access this application');
        }

        return $application;
    }
}