<?php

namespace App\Services\PermissionService;

use App\Models\Employeepermission;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;

class PermissionList
{
   
   public function handle(Request $request,$school_username)
     {
        $query = Employeepermission::query();  
        $query->with([
            'employee_user:id,name,email,phone,username,profile_picture,status',
            'exam:id,exam_name',
            'subject:id,subject_name',
            'sessionyear:id,sessionyear_name',
            'programyear:id,programyear_name',
            'level:id,level_name',
            'faculty:id,faculty_name',
            'department:id,department_name',
            'section:id,section_name',
        ])->where('school_username', $school_username);


        
           // Apply filters
       $filters = [
        'sessionyear_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'viewById' => 'id',
        'subject_id',
        'permission_role',
        'employee_user_id'
      ];

   foreach ($filters as $requestKey => $dbColumn) {
       // if $filters is associative, otherwise key = value
       if (is_int($requestKey)) $requestKey = $dbColumn;
       if ($request->filled($requestKey)) {
           $query->where($dbColumn, $request->$requestKey);
       }
   }


        
    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject_id', 'like', "%$search%")
                ->orWhere('permission_role', 'like', "%$search%")
                ->orWhere('employee_user_id', 'like', "%$search%");
        });
    }




        // Sorting
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page

        // Apply pagination
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' =>$result->items(),
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
           
        ]);
    }
}
