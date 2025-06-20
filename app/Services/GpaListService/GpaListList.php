<?php

namespace App\Services\GpaListService;

use App\Models\Gpacategory;
use App\Models\Gpalist;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class GpaListList
{

    public function handle(Request $request,$school_username) {
        $query = Gpalist::query();
        $query->with('gpaCategory'); 
        $query->where('school_username', $school_username);

    $filters = [
        'viewById' => 'id',
        'session_year' => 'session_year',
        'gpa_category_id' => 'gpa_category_id',
    ];

      foreach ($filters as $requestKey => $dbColumn) {
         if (is_int($requestKey)) $requestKey = $dbColumn;
         if ($request->filled($requestKey)) {
             $query->where($dbColumn, $request->$requestKey);
          }
       }
   
      if ($request->has('search')) {
          $search = $request->search;
          $query->where(function ($q) use ($search) {
               $q->where('session_year', 'like', "%$search%")
                 ->orWhere('status', 'like', "%$search%");
              });
         }

       if ($request->has('status')) {
           $query->where('status', $request->status);
         }

        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
       
        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->items(),
            'total' => $result->total(),
             'per_page' => $result->perPage(),
             'current_page' => $result->currentPage(),
             'last_page' => $result->lastPage(),
        ]);
    }
}
