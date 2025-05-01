<?php

namespace App\Services\InvoiceService;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;

class InvoiceList
{
   
   public function handle(Request $request,$school_username)
     {
        $query = Invoice::query();  
        $query->where('school_username', $school_username);
        $query->with('student:id,bangla_name,english_name,registration');



          // Apply filters
       $filters = [
         'session_id',
         'programyear_id',
         'level_id',
         'faculty_id',
         'department_id',
         'section_id',
         'student_id',
         'payment_status',
         'viewById' => 'id'
     ];

    foreach ($filters as $requestKey => $dbColumn) {
        // if $filters is associative, otherwise key = value
        if (is_int($requestKey)) $requestKey = $dbColumn;
        if ($request->filled($requestKey)) {
            $query->where($dbColumn, $request->$requestKey);
        }
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

        if ($request->has('student_id')) {
               $total_row_amount=$result->sum('total_amount');
        }else{
              $total_row_amount=0;
          }

        return response()->json([
            'data' =>$result,
            'total_row_amount' => $total_row_amount,
        ]);
    }
}
