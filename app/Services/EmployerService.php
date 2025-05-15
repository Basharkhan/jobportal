<?php
namespace App\Services;

use App\Repositories\AdminRepository;
use App\Repositories\EmployerRepository;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmployerService {
    public function __construct( protected EmployerRepository $employerRepository,
    protected AdminRepository $adminRepository ) {

    }

    // public function registerEmployer( array $data ) {
    //     return $this->employerRepository->register( $data );
    // }

    // public function loginEmployer( string $email, string $password ) {
    //     $employer = $this->employerRepository->findByEmail( $email );

    //     if ( !$employer ) {
    //         throw new AuthenticationException( 'Employer not found' );
    //     }

    //     if ( !$employer->hasRole( 'employer' ) ) {
    //         throw new UnauthorizedHttpException( 'Unauthorized', 'You are not authorized to access this resource.' );
    //     }

    //     $this->adminRepository->revokeAuthTokens( $employer, true );

    //     $this->employerRepository->validateCredentials( $employer, $password );
    //     return $employer->createToken( 'employer-token' )->plainTextToken;
    // }
}