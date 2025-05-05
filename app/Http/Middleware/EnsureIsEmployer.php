<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsEmployer {
    /**
    * Handle an incoming request.
    *
    * @param  \Closure( \Illuminate\Http\Request ): ( \Symfony\Component\HttpFoundation\Response )  $next
    */

    public function handle( Request $request, Closure $next ): Response {
        if ( !$request->user()->hasRole( 'employer' ) ) {
            return response()->json( [
                'message' => 'Employer account required',
                'errors' => [ 'role' => [ 'Only employers can access this route' ] ]
            ], 403 );
        }
        return $next( $request );
    }
}
