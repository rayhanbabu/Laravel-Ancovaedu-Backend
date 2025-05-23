<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use App\Models\Enroll;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;

class PaymentList
{ 
   public function handle(Request $request,$school_username)
     {
          $query = Payment::query();  
          $query->with('invoices');
          $query->with([
           'student',
           'enroll.sessionyear:id,sessionyear_name',
           'enroll.programyear:id,programyear_name',
           'enroll.level:id,level_name',
           'enroll.faculty:id,faculty_name',
           'enroll.department:id,department_name',
           'enroll.section:id,section_name',
          ]);
         $query->with(['creator:id,name,username', 'updater:id,name,username']);
         $query->where('school_username', $school_username);


         $query->whereHas('enroll', function ($q) use ($request) {
                 $filterFields = [
                    'sessionyear_id',
                    'programyear_id',
                    'level_id',
                    'faculty_id',
                    'department_id',
                    'section_id',
                    'student_id',
                 
                ];

                foreach ($filterFields as $field) {
                    if ($request->filled($field)) {
                        $q->where($field, $request->$field);
                    }
                }
            });


        if ($request->has('viewById')) {
            $query->where('id', $request->viewById);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

         if ($request->has('created_by')) {
             $query->where('created_by', $request->created_by);
         }
        
         if ($request->has('date_range') && $request->date_range == 1) {
             $startDate = date('Y-m-d', strtotime($request->start_date));
             $endDate = date('Y-m-d', strtotime($request->end_date));

            $query->whereBetween('date', [$startDate, $endDate]);
          }
        
    
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

    
        $query->whereDate('created_at', '=', "$year-$month-$day");
    } elseif ($request->has('year') && $request->has('month')) {
        $year = $request->input('year');
        $month = $request->input('month');

     
        $query->whereYear('created_at', '=', $year)
              ->whereMonth('created_at', '=', $month);
    } elseif ($request->has('year')) {
        $year = $request->input('year');

        $query->whereYear('created_at', '=', $year);
    }



        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortField, $sortDirection);

       
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
       
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
