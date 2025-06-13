<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserAuthRepository;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserAuthService {
    public function __construct( protected UserAuthRepository $userAuthRepository ) {

    }

    public function registerAdmin( array $data ) {
        return $this->userAuthRepository->registerAdmin( [
            'name' => $data[ 'name' ],
            'email' => $data[ 'email' ],
            'password' => $data[ 'password' ],
            'email_verified_at' => now(),
        ] );
    }

    public function login( string $email, string $password, $expectedRole ) {
        $user = $this->userAuthRepository->findByEmail( $email );

        if ( !$user || !$user->hasRole($expectedRole)) {
            throw new AuthenticationException( 'Invalid credentils' );
        }               

        $this->userAuthRepository->validateCredentials( $user, $password );
        $this->userAuthRepository->revokeAuthTokens( $user, true );

        return $user->createToken( 'user-token')->plainTextToken;
    }

    public function logout( User $user, bool $revokeAll = false ) {
        $this->userAuthRepository->revokeAuthTokens( $user, $revokeAll );
    }

    public function registerEmployer( array $data ) {
        return $this->userAuthRepository->registerEmployer( $data );
    }

    public function registerUser( array $data ) {
        return $this->userAuthRepository->registerUser( $data );
    }
}