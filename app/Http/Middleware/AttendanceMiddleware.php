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

        }

      if ($roleType === 'Employee' && user()->employee?->school_username === $school_username) {
            $permissions = user()->permissions();

             $filters = [
                    'sessionyear_id' => $request->sessionyear_id,
                    'programyear_id' => $request->programyear_id,
                    'level_id'       => $request->level_id,
                    'faculty_id'     => $request->faculty_id,
                    'department_id'  => $request->department_id,
                    'section_id'     => $request->section_id,
                    'subject_id'     => $request->subject_id,
                ];

            $access_group = $request->query('access_group'); 
         
          $query = $permissions->where('permission_role', 'AttendanceByGroup')
           ->where(function ($q) use ($filters, $access_group) {
                $q->where($filters);

             $q->orWhere('access_group', $access_group);
            });

              if ($query->exists()) {
                    return $next($request);
              }
         }

     return response()->json([
         'status'  => 'error',
         'message' => 'Forbidden: You are not authorized to perform this action.',
     ], 403);

    }
}
