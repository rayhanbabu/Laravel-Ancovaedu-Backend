<?php

namespace App\Services\LevelService;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelList
{
   
public function handle(Request $request,$school_username)
    {
        $query = Level::query();
        $query->where('school_username', $school_username);

        // Search
        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('level_name', 'like', "%$search%")
                    ->orWhere('level_status', 'like', "%$search%");
             });
         }

        // Filter by status
        if ($request->has('status')) {
                $query->where('level_status', $request->status);
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
