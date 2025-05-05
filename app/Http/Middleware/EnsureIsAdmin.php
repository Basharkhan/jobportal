<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin {
    /**
    * Handle an incoming request.
    *
    * @param  \Closure( \Illuminate\Http\Request ): ( \Symfony\Component\HttpFoundation\Response )  $next
    */

    public function handle( Request $request, Closure $next ): Response {
        if ( !$request->user()->hasRole( 'super_admin' ) ) {
            return response()->json( [
                'message' => 'Unauthorized. Super admin access required.',
                'errors' => [ 'role' => [ 'Only super admin can access this route' ] ]
            ], 403 );
        }
        return $next( $request );
    }
}
