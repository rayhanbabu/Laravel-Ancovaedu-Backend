<?php

namespace App\Services\PayroleService;

use App\Models\Payrole;
use Illuminate\Http\Request;

class PayroleList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Payrole::query();
        $query->with('user'); // Eager load the user relationship
        $query->where('school_username', $school_username);
    
        // Search
        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%$search%")
                    ->orWhere('college_sallary', 'like', "%$search%");
             });
         }


           // Apply filters
       $filters = [
          'employee_id',
          'viewById' => 'id'
      ];

   foreach ($filters as $requestKey => $dbColumn) {
       // if $filters is associative, otherwise key = value
       if (is_int($requestKey)) $requestKey = $dbColumn;
       if ($request->filled($requestKey)) {
           $query->where($dbColumn, $request->$requestKey);
       }
   }


   if ($request->has('year') && $request->has('month')) {
    $year = $request->input('year');
    $month = $request->input('month');

       // Filter by month and year
      $query->whereYear('payrole_date', '=', $year)
          ->whereMonth('payrole_date', '=', $month);
    } elseif ($request->has('year')) {
        $year = $request->input('year');

     // Filter by year
      $query->whereYear('payrole_date', '=', $year);
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
              'data' =>$result->items(),
              'total' => $result->total(),
              'per_page' => $result->perPage(),
              'current_page' => $result->currentPage(),
              'last_page' => $result->lastPage(),
                  
         ]);
    }
}
