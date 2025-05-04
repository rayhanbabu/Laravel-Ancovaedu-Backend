<?php

namespace App\Services\AdmitCardService;

use App\Models\Admitcard;
use Illuminate\Http\Request;
use App\Http\Resources\AdmitCardResource;

class AdmitCardList
{
   
   public function handle(Request $request,$school_username)
     {
        $query = Admitcard::query();  
        $query->with('subject:id,subject_name,subject_code'); // Eager load the subject relationship
        $query->where('school_username', $school_username);

        
    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject_id', 'like', "%$search%")
                ->orWhere('date', 'like', "%$search%")
                ->orWhere('time', 'like', "%$search%");
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
            'data' =>$result,
            'pagination' => [
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
            ]
        ]);
    }
}
