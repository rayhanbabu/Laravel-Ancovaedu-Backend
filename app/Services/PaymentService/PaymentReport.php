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


              // Apply filters
        
    
      // View By Id
        if ($request->has('viewById')) {
            $query->where('id', $request->viewById);
        }

         // View By Id
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
    
        // Pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page
    
           // Aggregation + Grouping
    $query->selectRaw("
        enrolls.student_id, 
        MAX(students.english_name) as english_name,
        MAX(students.bangla_name) as bangla_name,
        MAX(enrolls.roll) as roll,
        SUM(invoices.amount) as invoice_amount, 
        SUM(invoices.waiver_amount) as waiver_amount, 
        SUM(invoices.total_amount) as net_invoice_amount, 
        COUNT(*) as total_invoices,
        MAX(enrolls.sessionyear_id) as sessionyear_id, 
        MAX(enrolls.programyear_id) as programyear_id,
        MAX(enrolls.level_id) as level_id,
        MAX(enrolls.faculty_id) as faculty_id,
        MAX(enrolls.department_id) as department_id,
        MAX(enrolls.section_id) as section_id,
        SUM(invoices.partial_payment) as partial_payment, 
        SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END) as full_payment,
        (
            SUM(invoices.partial_payment) + SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END)
        ) as total_payment,
        (
            SUM(invoices.total_amount) - (SUM(invoices.partial_payment) + SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END))
        ) as total_due_amount
    ")
     ->groupBy('enrolls.student_id');
    
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
