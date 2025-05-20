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
    $query = Invoice::query()
        ->join('enrolls', 'invoices.enroll_id', '=', 'enrolls.id')
        ->join('students', 'enrolls.student_id', '=', 'students.id')
        ->where('invoices.school_username', $school_username);

    // Apply enroll filters from request
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
                $q->where($field, $request->input($field));
            }
        }
    });

    // View by specific invoice ID
    if ($request->filled('viewById')) {
        $query->where('invoices.id', $request->input('viewById'));
    }

    // Filter by payment status
    if ($request->filled('payment_status')) {
        $query->where('invoices.payment_status', $request->input('payment_status'));
    }

    // Pagination
    $perPage = min((int) $request->input('perPage', 10), 100); // Max 100
    $page = (int) $request->input('page', 1);

                // Main select and grouping
            $query->selectRaw("
                enrolls.student_id, 
                MAX(students.english_name) as english_name,
                MAX(students.bangla_name) as bangla_name,
                MAX(enrolls.roll) as roll,
                MAX(invoices.invoice_group) as invoice_group, 
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
                    SUM(invoices.partial_payment) + 
                    SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END)
                ) as total_payment,
                (
                    SUM(invoices.total_amount) - 
                    (
                        SUM(invoices.partial_payment) + 
                        SUM(CASE WHEN invoices.payment_status = '1' THEN invoices.total_amount ELSE 0 END)
                    )
                ) as total_due_amount
            ")
            ->groupBy('enrolls.student_id');


    // Paginate results
    $paginated = $query->paginate($perPage, ['*'], 'page', $page);

    // Format data for response
    $paginated->getCollection()->transform(function ($item) {
        $item->roll = (int) $item->roll;
        $item->student_id = (int) $item->student_id;
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
        $item->total_payment = (int) $item->total_payment;
        $item->total_due_amount = (int) $item->total_due_amount;
        return $item;
    });

    // Return JSON response
    return response()->json([
        'data' => $paginated->items(),
        'total' => $paginated->total(),
        'per_page' => $paginated->perPage(),
        'current_page' => $paginated->currentPage(),
        'last_page' => $paginated->lastPage(),
        'from' => $paginated->firstItem(),
        'to' => $paginated->lastItem(),
    ]);
}

}
