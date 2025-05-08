<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\AdminRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdminService {
    public function __construct( protected AdminRepository $adminRepository ) {

    }

    public function registerAdmin( array $data ) {
        // $this->validateAdminSecret( $data[ 'secret_key' ] ?? null );

        return $this->adminRepository->register( [
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

    public function loginAdmin( string $email, string $password ) {
        $admin = $this->adminRepository->findByEmail( $email );

        if ( !$admin ) {
            throw new AuthenticationException( 'Admin not found' );
        }

        if ( !$admin->hasRole( 'admin' ) ) {
            throw new UnauthorizedHttpException( 'Unauthorized', 'You are not authorized to access this resource.' );
        }

        $this->adminRepository->revokeAuthTokens( $admin, true );

        $this->adminRepository->validateCredentials( $admin, $password );
        return $admin->createToken( 'admin-token', [ '*' ], now()->addHour( 12 ) )->plainTextToken;
    }

    public function logoutAdmin( User $user, bool $revokeAll = false ) {
        $this->adminRepository->revokeAuthTokens( $user, $revokeAll );
    }
}