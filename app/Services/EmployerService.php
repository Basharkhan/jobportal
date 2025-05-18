<?php
namespace App\Services;

use App\Models\JobPosting;
use App\Repositories\EmployerRepository;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmployerService {
    public function __construct( protected EmployerRepository $employerRepository ) {

    }

    public function createJob( array $data ): ?JobPosting {
        return $this->employerRepository->createJob( $data );
    }
}