<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsJobSeeker {
    /**
    * Handle an incoming request.
    *
    * @param  \Closure( \Illuminate\Http\Request ): ( \Symfony\Component\HttpFoundation\Response )  $next
    */

    public function handle( Request $request, Closure $next ): Response {
        if ( !$request->user()->hasRole( 'job_seeker' ) ) {
            return response()->json( [
                'message' => 'Unauthorized. Job seeker access required.',
                'errors' => [ 'role' => [ 'Only job seeker can access this route' ] ]
            ], 403 );
        }
        return $next( $request );
    }
}
