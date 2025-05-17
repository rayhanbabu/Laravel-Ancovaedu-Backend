<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Enroll;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentReportResource;

class PaymentReport
{ 


    public function handle(Request $request, $school_username)
    {
        $query = Invoice::query();
         $query->join('enrolls', 'invoices.enroll_id', '=', 'enrolls.id');
           $query->join('students', 'enrolls.student_id', '=', 'students.id');
           

       // Apply school filter
           $query->where('invoices.school_username', $school_username);

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
        
    
       
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page
    
                    
        $query->selectRaw("
                enrolls.student_id, 
                MAX(students.english_name) as english_name,
                MAX(students.bangla_name) as bangla_name,
                CAST(MAX(enrolls.roll) AS UNSIGNED) as roll,
                CAST(SUM(invoices.amount) AS UNSIGNED) as invoice_amount, 
                CAST(SUM(invoices.waiver_amount) AS UNSIGNED) as waiver_amount, 
                CAST(SUM(invoices.total_amount) AS UNSIGNED) as net_invoice_amount, 
                COUNT(*) as total_invoices,
                CAST(MAX(enrolls.sessionyear_id) AS UNSIGNED) as sessionyear_id, 
                CAST(MAX(enrolls.programyear_id) AS UNSIGNED) as programyear_id,
                CAST(MAX(enrolls.level_id) AS UNSIGNED) as level_id,
                CAST(MAX(enrolls.faculty_id) AS UNSIGNED) as faculty_id,
                CAST(MAX(enrolls.department_id) AS UNSIGNED) as department_id,
                CAST(MAX(enrolls.section_id) AS UNSIGNED) as section_id,
                CAST(SUM(invoices.partial_payment) AS UNSIGNED) as partial_payment, 
                CAST(SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END) AS UNSIGNED) as full_payment,
                (
                    CAST(SUM(invoices.partial_payment) AS UNSIGNED) + CAST(SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END) AS UNSIGNED)
                ) as total_payment,
                (
                    CAST(SUM(invoices.total_amount) AS UNSIGNED) - (
                        CAST(SUM(invoices.partial_payment) AS UNSIGNED) + CAST(SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END) AS UNSIGNED)
                    )
                ) as total_due_amount
            ")
            ->groupBy('enrolls.student_id');


             $results = $query->get()->map(function ($item) {
              $item->roll = (int) $item->roll;
              $item->student_id = (int) $item->student_id;
              $item->english_name =$item->english_name;
              $item->bangla_name =  $item->bangla_name;
              $item->invoice_amount = (int) $item->invoice_amount;
              $item->waiver_amount = (int) $item->waiver_amount;
              $item->net_invoice_amount = (int) $item->net_invoice_amount;
              $item->total_invoices = (int) $item->total_invoices;
              $item->sessionyear_id = (int) $item->sessionyear_id;
              $item->programyear_id = (int) $item->programyear_id;
              $item->level_id = (int) $item->level_id;
              $item->faculty_id = (int) $item->faculty_id;
              $item->department_id = (int) $item->department_id;
              $item->section_id = (int) $item->section_id;
              $item->partial_payment = (int) $item->partial_payment;
              $item->full_payment = (int) $item->full_payment;
              $item->total_due_amount = (int) $item->total_due_amount;
           
            return $item;
           });


    
        // Paginate result
        $result = $query->paginate($perPage, ['*'], 'page', $page);
    
        return response()->json([
             'data' => $result->items(),
             'total' => $result->total(),
             'per_page' => $result->perPage(),
             'current_page' => $result->currentPage(),
             'last_page' => $result->lastPage(),
             'from' => $result->firstItem(),
             'to' => $result->lastItem()
            
        ]);
    }
}
