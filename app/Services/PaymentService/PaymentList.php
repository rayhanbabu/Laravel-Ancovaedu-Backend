<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;

class PaymentList
{ 
   public function handle(Request $request,$school_username)
     {
        $query = Payment::query();  
        $query->with('invoices');
        $query->with('student:id,bangla_name,english_name,registration');
        $query->where('school_username', $school_username);


        
         // Apply filters
       $filters = [
        'sessionyear_id',
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
                ->orWhere('payment_type', 'like', "%$search%")
                ->orWhere('tran_id', 'like', "%$search%");
        });
    }


    if ($request->has('year') && $request->has('month') && $request->has('day')) {
         $year = $request->input('year');
         $month = $request->input('month');
         $day = $request->input('day');

        // Filter by date
        $query->whereDate('created_at', '=', "$year-$month-$day");
    } elseif ($request->has('year') && $request->has('month')) {
        $year = $request->input('year');
        $month = $request->input('month');

        // Filter by month and year
        $query->whereYear('created_at', '=', $year)
              ->whereMonth('created_at', '=', $month);
    } elseif ($request->has('year')) {
        $year = $request->input('year');

        // Filter by year
        $query->whereYear('created_at', '=', $year);
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
