<?php

namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Enroll;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;

class InvoiceList
{
   
   public function handle(Request $request,$school_username)
     {
        $query = Invoice::query();  
        $query->where('school_username', $school_username);
        $query = Invoice::with([
           'student',
           'enroll.sessionyear:id,sessionyear_name',
           'enroll.programyear:id,programyear_name',
           'enroll.level:id,level_name',
           'enroll.faculty:id,faculty_name',
           'enroll.department:id,department_name',
           'enroll.section:id,section_name',
        ])->where('school_username', $school_username);
     

       $query->whereHas('enroll', function ($q) use ($request) {
                 $filterFields = [
                    'sessionyear_id',
                    'programyear_id',
                    'level_id',
                    'faculty_id',
                    'department_id',
                    'section_id',
                    'student_id',
                    'payment_status',
                ];

                foreach ($filterFields as $field) {
                    if ($request->filled($field)) {
                        $q->where($field, $request->$field);
                    }
                }
            });

         if ($request->has('invoice_group')) {
                $query->where('invoice_group', $request->invoice_group);
         }

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('amount', 'like', "%$search%")
                ->orWhere('fee_type', 'like', "%$search%")
                ->orWhere('desc', 'like', "%$search%");
        });
    }
  


        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);

        $result = $query->paginate($perPage, ['*'], 'page', $page);

        if ($request->has('student_id')) {
               $total_row_amount=$result->sum('total_amount');
        }else{
              $total_row_amount=0;
          }

        return response()->json([
            'data' =>$result->items(),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'total_amount_row' => $total_row_amount,
        ]);
    }
}
