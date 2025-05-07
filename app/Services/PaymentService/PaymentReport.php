<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentReportResource;

class PaymentReport
{ 


    public function handle(Request $request, $school_username)
    {
        $query = Invoice::query();
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
          ];
    
        foreach ($filters as $key) {
             if ($request->filled($key)) {
                 $query->where($key, $request->input($key));
             }
         }
    
        // Pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $perPage = ($perPage > 100) ? 100 : $perPage; // Max 100 per page
    
        // Aggregation + Grouping
        $query->selectRaw("
            student_id, 
            SUM(amount) as invoice_amount, 
            SUM(waiver_amount) as waiver_amount, 
            SUM(total_amount) as net_invoice_amount, 
            COUNT(*) as total_invoices,
            MAX(sessionyear_id) as sessionyear_id, 
            MAX(programyear_id) as programyear_id,
            MAX(level_id) as level_id,
            MAX(faculty_id) as faculty_id,
            MAX(department_id) as department_id,
            MAX(section_id) as section_id,
            SUM(partial_payment) as partial_payment, 
            SUM(CASE WHEN payment_status = '1' THEN total_amount ELSE 0 END) as full_payment,
    
            (
        SUM(partial_payment) + SUM(CASE WHEN payment_status = '1' THEN total_amount ELSE 0 END)) as total_payment,

          (SUM(total_amount) - (SUM(partial_payment) + SUM(CASE WHEN payment_status = '1' THEN total_amount ELSE 0 END))
              ) as total_due_amount
          ")
        ->groupBy('student_id');
    
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
