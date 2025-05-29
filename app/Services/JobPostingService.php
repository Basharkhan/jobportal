<?php
namespace App\Services;

use App\Models\JobPosting;
use App\Repositories\JobPostingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
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
            throw new UnauthorizedHttpException( 'Unauthorized! You are not allowed to access this api' );
        }

        return $this->jobPostingRepository->getJobsByEmployerId( $employerId, $perPage );
    }

    public function getAllJobs( int $perPage = 10 ): LengthAwarePaginator {        
        return $this->jobPostingRepository->getAllJobs( $perPage );           
    }

    public function findJobById( int $jobId ) {
        $job = $this->jobPostingRepository->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }       

        return $job;
    }    

    public function getJobByIdForEmployer(int $jobId) {        
        $user = auth()->user();
        $job = $this->jobPostingRepository->findJobById($jobId);
        
        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }  

        if($user && $user->id !== $job->user_id) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'You are not allowed to access this resource!'
            ], Response::HTTP_UNAUTHORIZED));
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
        $user = auth()->user();
        $job = $this->findJobById( $jobId );

        if($user->isAdmin()) {
            return $this->jobPostingRepository->changeJobStatus( $jobId, $status );
        }

        if($user->isEmployer() && $user->id === $job->user_id) {
            return $this->jobPostingRepository->changeJobStatus( $jobId, $status );
        }

        throw new UnauthorizedHttpException( 'Unauthorized! You are not allowed to access this api' );
    }

    public function searchEmployerJobs(int $employerId, array $filters, int $perPage = 10): LengthAwarePaginator {
        return $this->jobPostingRepository->searchEmployerJobs($employerId, $filters, $perPage);    
    }
}