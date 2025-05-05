<?php

namespace App\Services\SubjectService;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Subject::query();
        $query->where('school_username', $school_username)
        ->where('sessionyear_id', $request->sessionyear_id)
        ->where('programyear_id', $request->programyear_id)
        ->where('level_id', $request->level_id)
        ->where('faculty_id', $request->faculty_id)
        ->where('department_id', $request->department_id)
        ->where('section_id', $request->section_id);

    
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
