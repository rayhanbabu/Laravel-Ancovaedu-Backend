<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
        $school_username = $request->route('school_username');
        $roleType = user()->user_role->role_type;

      
        if (in_array($roleType, ['Supperadmin', 'Manager'])) {
            return $next($request);
        }

        if ($roleType === 'Agent' && user()->agent?->school_username === $school_username) {
            return $next($request);
        }

        if ($roleType === 'School' && user()->username === $school_username) {
            return $next($request);
        }


        if ($roleType === 'Employee' && user()->employee?->school_username === $school_username) {
            $permissions = user()->permissions();

            if ($permissions->where('permission_role', 'Attendance')->exists()) {
                return $next($request);
            }

            $filters = [
                'sessionyear_id' => $request->query('sessionyear_id'),
                'programyear_id' => $request->query('programyear_id'),
                'level_id'       => $request->query('level_id'),
                'faculty_id'     => $request->query('faculty_id'),
                'department_id'  => $request->query('department_id'),
                'section_id'     => $request->query('section_id'),
            ];

            if ($permissions->where('permission_role', 'AttendanceByGroup')->where($filters)->exists()) {
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
