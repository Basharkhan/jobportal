<?php
namespace App\Services\Auth;

use App\Repositories\Auth\AdminAuthRepository;
use Illuminate\Auth\Access\AuthorizationException;

class AdminAuthService {
    public function __construct( protected AdminAuthRepository $adminAuthRepository ) {

    }

    public function register( array $data ) {
        $this->validateAdminSecret( $data[ 'secret_key' ] ?? null );

        return $this->adminAuthRepository->register( [
            'name' => $data[ 'name' ],
            'email' => $data[ 'email' ],
            'password' => bcrypt( $data[ 'password' ] ),
            'email_verified_at' => now(),
        ] );
    }

    private function validateAdminSecret( ?string $secret ): void {
        if ( !hash_equals( config( 'app.admin_secret' ), $secret ) ) {
            throw new AuthorizationException( 'Invalid admin secret key' );
        }
    }
}