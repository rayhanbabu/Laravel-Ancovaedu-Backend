<?php

namespace App\Services\PayroleinfoService;

use App\Models\Payroleinfo;
use Illuminate\Http\Request;

class PayroleinfoList
{
   
public function handle(Request $request,$school_username)
    
    {
        $query = Payroleinfo::query();
        $query->with('user'); // Eager load the user relationship
        $query->where('school_username', $school_username);
    

        if ($request->has('search')) {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%$search%")
                    ->orWhere('college_sallary', 'like', "%$search%");
             });
         }

     
        if ($request->has('status')) {
                $query->where('payroleinfo_status', $request->status);
        }

      
        if ($request->has('viewById')) {
            $query->where('id', $request->viewById);
        }

      
        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

   
        $perPage = (int) $request->input('perPage', 20);
        $page = (int) $request->input('page', 1);
       

       
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
