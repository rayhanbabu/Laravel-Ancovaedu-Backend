<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class School
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $school_username = $request->route('school_username');

    $roleType = user()->user_role->role_type;

    if (in_array($roleType, ['Supperadmin', 'Manager'])) {
        return $next($request);
    } 

    if ($roleType == 'Agent' && user()->agent?->school_username == $school_username) {
        return $next($request);
    }

    if ($roleType == 'School' && user()->username == $school_username) {
        return $next($request);
    }

       return response()->json([
          'status'  => 'error',
          'message' => 'Forbidden: You are not authorized to perform this action.',
      ], 403);
   }
}
