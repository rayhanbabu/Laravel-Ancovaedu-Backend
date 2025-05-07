<?php

namespace App\Services\SessionService;

use App\Models\Sessionyear;
use Illuminate\Http\Request;

class SessionList
{
   
    public function handle(Request $request, $school_username)
    {
        try {
            $query = Sessionyear::query();
            $query->where('school_username', $school_username);
            
            // Search - Using parameter binding for security
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('sessionyear_name', 'like', '%' . $search . '%')
                      ->orWhere('sessionyear_status', 'like', '%' . $search . '%');
                });
            }
            
            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('sessionyear_status', $request->status);
            }
            
            // View By Id
            if ($request->has('viewById') && is_numeric($request->viewById)) {
                $query->where('id', (int) $request->viewById);
            }
            
            // Sorting
            $allowedSortFields = ['id', 'sessionyear_name', 'sessionyear_status', 'created_at']; // Define allowed fields
            $sortField = in_array($request->get('sortField'), $allowedSortFields) ? 
                        $request->get('sortField') : 'id';
            $sortDirection = in_array($request->get('sortDirection'), ['asc', 'desc']) ? 
                            $request->get('sortDirection') : 'asc';
            $query->orderBy($sortField, $sortDirection);
            
            // Pagination
            $perPage = min((int) $request->input('perPage', 20), 100); // Max 100 per page
            $page = max((int) $request->input('page', 1), 1); // Ensure page is at least 1
            
            // Apply pagination
            $result = $query->paginate($perPage, ['*'], 'page', $page);
            
            return response()->json([
                'data' => $result->items(),
                'total' => $result->total(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
             ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing the request',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
