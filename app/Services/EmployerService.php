<?php
namespace App\Services;

use App\Models\JobPosting;
use App\Repositories\EmployerRepository;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmployerService {
    public function __construct( protected EmployerRepository $employerRepository ) {

    }

    public function createJob( array $data ): ?JobPosting {
        $data['user_id'] = auth()->user()->id;
        return $this->employerRepository->createJob( $data );
    }

    public function getJobsByEmployerId( int $employerId ) {
        if ( auth()->user()->id !== $employerId ) {
            throw new UnauthorizedHttpException( 'Unauthorized' );
        }

        return $this->employerRepository->getJobsByEmployerId( $employerId );
    }

    public function findJobById( int $jobId ) {
        $job = $this->employerRepository->findJobById( $jobId );

        if ( !$job ) {
            throw new NotFoundHttpException( 'Job not found' );
        }

        if ( auth()->user()->id !== $job->user_id ) {
            throw new UnauthorizedHttpException( 'Unauthorized' );
        }

        return $job;
    }
}