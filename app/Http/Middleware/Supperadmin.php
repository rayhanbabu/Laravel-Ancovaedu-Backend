<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Supperadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (user()->user_role->role_type !== 'Supperadmin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
        return $next($request);
    }
}
