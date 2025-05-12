<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstitutionFinanaceMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $school_username = $request->route('school_username');
        $roleType = user()->user_role->role_type;

        // Allow Supperadmin and Manager
        if (in_array($roleType, ['Supperadmin', 'Manager'])) {
            return $next($request);
        }

        // Allow Agent if school matches
        if ($roleType === 'Agent' && user()->agent?->school_username === $school_username) {
            return $next($request);
        }

        // Allow School if username matches
        if ($roleType === 'School' && user()->username === $school_username) {
            return $next($request);
        }

        // Employee permissions check
        if ($roleType === 'Employee' && user()->employee?->school_username === $school_username) {
            $permissions = user()->permissions();

            // Check for 'StudentInfromation' permission
            if ($permissions->where('permission_role', 'InstitutionFinanace')->exists()) {
                return $next($request);
            }

        }

        // Unauthorized
        return response()->json([
            'status'  => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
