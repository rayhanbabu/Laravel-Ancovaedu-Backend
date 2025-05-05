<?php

namespace App\Services\DesignationService;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Designation::query();
        $query->where('school_username', $school_username);
    
        // Search
        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('designation_name', 'like', "%$search%")
                    ->orWhere('designation_status', 'like', "%$search%");
             });
         }

        // Filter by status
        if ($request->has('status')) {
                $query->where('designation_status', $request->status);
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
