<?php

namespace App\Services\FacultyService;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyList
{
   
public function handle(Request $request,$school_username)
    {
        $query = Faculty::query();
        $query->with('level');
        $query->where('school_username', $school_username);
       
        // Search
        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('faculty_name', 'like', "%$search%")
                    ->orWhere('faculty_status', 'like', "%$search%");
             });
         }

        // Filter by status
        if ($request->has('status')) {
                $query->where('faculty_status', $request->status);
        }

        // Filter by level_id
        if ($request->has('level_id')) {
            $query->where('level_id', $request->level_id);
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
