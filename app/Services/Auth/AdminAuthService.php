<?php
namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AdminAuthRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdminAuthService {
    public function __construct( protected AdminAuthRepository $adminAuthRepository ) {

    }

    public function registerAdmin( array $data ) {
        // $this->validateAdminSecret( $data[ 'secret_key' ] ?? null );

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

    public function loginAdmin( string $email, string $password ) {
        $admin = $this->adminAuthRepository->findByEmail( $email );

        if ( !$admin ) {
            throw new AuthenticationException( 'Admin not found' );
        }

        if ( !Hash::check( $password, $admin->password ) ) {
            throw new UnauthorizedHttpException( 'Invalid credentials' );
        }

        return $admin->createToken( 'admin-token' )->plainTextToken;
    }

}