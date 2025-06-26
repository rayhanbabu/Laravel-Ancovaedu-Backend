<?php

namespace App\Services\FeeService;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Http\Resources\FeeResource;

class FeeList
{
   
   public function handle(Request $request,$school_username)
     {
        $query = Fee::with('feetype')->where('school_username', $school_username);

        // Apply filters
        $filters = [
            'sessionyear_id',
            'programyear_id',
            'level_id',
            'faculty_id',
            'department_id',
            'section_id',
            'feetype_id',
            'viewById' => 'id'
        ];

   foreach ($filters as $requestKey => $dbColumn) {
       // if $filters is associative, otherwise key = value
       if (is_int($requestKey)) $requestKey = $dbColumn;
       if ($request->filled($requestKey)) {
           $query->where($dbColumn, $request->$requestKey);
       }
   }

    // View by specific fee ID
    if ($request->filled('fee_group')) {
        $query->where('fee_group', $request->fee_group);
    }
  
    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('amount', 'like', "%$search%")
                ->orWhere('fee_type', 'like', "%$search%")
                ->orWhere('desc', 'like', "%$search%");
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
