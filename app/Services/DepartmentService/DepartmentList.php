<?php

namespace App\Services\DepartmentService;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentList
{
   
public function handle(Request $request,$school_username, $faculty_id)
    
    {
        $query = Department::query();
        $query->where('school_username', $school_username);
        $query->where('faculty_id', $faculty_id);
        // Search
        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('department_name', 'like', "%$search%")
                    ->orWhere('department_status', 'like', "%$search%");
             });
         }

        // Filter by status
        if ($request->has('status')) {
                $query->where('department_status', $request->status);
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
              'data' =>$result,
                  
         ]);
    }
}
