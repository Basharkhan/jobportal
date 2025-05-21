<?php
namespace App\Services;

use App\Models\JobPosting;
use App\Repositories\EmployerRepository;
use App\Repositories\JobPostingRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JobPostingService {
    public function __construct(protected JobPostingRepository $jobPostingRepository) {
        
    }
    
    public function createJob( array $data ): ?JobPosting {
        $data['user_id'] = auth()->user()->id;
        return $this->jobPostingRepository->createJob( $data );
    }

    public function getJobsByEmployerId( int $employerId, int  $perPage) {
        if ( auth()->user()->id !== $employerId ) {
            throw new UnauthorizedHttpException( 'Unauthorized' );
        }

        return $this->jobPostingRepository->getJobsByEmployerId( $employerId, $perPage );
    }

    public function findJobById( int $jobId ) {
        $job = $this->jobPostingRepository->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }

        if ( auth()->user()->id !== $job->user_id ) {
            throw new UnauthorizedHttpException( 'Unauthorized' );
        }

        return $job;
    }    

    public function updateJob( int $jobId, array $data ): ?JobPosting {
        $job = $this->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }

        return $this->jobPostingRepository->updateJob( $jobId, $data );
    }

    public function deleteJob( int $jobId ): bool {
        $job = $this->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }

        return $this->jobPostingRepository->deleteJob( $jobId );
    }

    public function changeJobStatus( int $jobId, string $status ): bool {
        $job = $this->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }

        return $this->jobPostingRepository->changeJobStatus( $jobId, $status );
    }

    public function searchEmployerJobs(int $employerId, array $filters, int $perPage = 10): LengthAwarePaginator {
        return $this->jobPostingRepository->searchEmployerJobs($employerId, $filters, $perPage);    
    }
}