<?php

namespace App\Services\SubjectService;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Subject::query();
        $query->where('school_username', $school_username);


       // Apply filters
       $filters = [
        'sessionyear_id',
        'programyear_id',
        'level_id',
        'faculty_id',
        'department_id',
        'section_id',
        'subject_group',
        'viewById' => 'id'
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
                $q->where('subject_name', 'like', "%$search%")
                    ->orWhere('subject_code', 'like', "%$search%");
             });
         }

        // Filter by status
        if ($request->has('status')) {
                $query->where('subject_status', $request->status);
        }

        // View By Id
        if ($request->has('viewById')) {
            $query->where('id', $request->viewById);
        }

         // Sorting
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

         // Pagination
        $perPage = (int) $request->input('perPage', 20);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page

         // Apply pagination
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->items(),
            'total' => $result->total(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'per_page' => $result->perPage(),
                  
         ]);
    }
}
