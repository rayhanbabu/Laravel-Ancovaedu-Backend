<?php
namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Fee;
use App\Models\Enroll;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class InvoiceSingleAdd
{
    public function handle($request, $school_username)
    {

    DB::beginTransaction();
    try {
        $user_auth = user();
        $school_username = $request->school_username;

        $validator = validator($request->all(), [
            'sessionyear_id' => 'required|integer|exists:sessionyears,id',
            'programyear_id' => 'required|integer|exists:programyears,id',
            'level_id' => 'required|integer|exists:levels,id',
            'faculty_id' => 'required|integer|exists:faculties,id',
            'department_id' => 'required|integer|exists:departments,id',
            'section_id' => 'required|integer|exists:sections,id',
            'student_id' => 'required|integer|exists:students,id',
            'fee_id' => 'required|array',
            'fee_id.*' => 'integer|exists:fees,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $fee_ids = $request->fee_id;
        $fee_ids = is_array($fee_ids) ? $fee_ids : [$fee_ids];
        $fee_ids = array_unique($fee_ids);
        $fee_ids = array_filter($fee_ids);

        $createdCount = 0;

        foreach ($fee_ids as $fee_id) {
            $query = Invoice::query();
            $query->where('school_username', $school_username)
                ->where('fee_id', $fee_id)
                ->with('enroll')
                ->whereHas('enroll', function ($q) use ($request) {
                    $q->where('sessionyear_id', $request->sessionyear_id)
                        ->where('programyear_id', $request->programyear_id)
                        ->where('level_id', $request->level_id)
                        ->where('faculty_id', $request->faculty_id)
                        ->where('department_id', $request->department_id)
                        ->where('section_id', $request->section_id);
                });

            $invoice = $query->first();

            if (!$invoice) {
                $fee_list = Fee::find($fee_id);

                // Get enroll for this student and criteria
                $enroll = Enroll::where('student_id', $request->student_id)
                    ->where('school_username', $school_username)
                    ->where('sessionyear_id', $request->sessionyear_id)
                    ->where('programyear_id', $request->programyear_id)
                    ->where('level_id', $request->level_id)
                    ->where('faculty_id', $request->faculty_id)
                    ->where('department_id', $request->department_id)
                    ->where('section_id', $request->section_id)
                    ->first();

                if (!$enroll) {
                    continue; // Skip if no enroll record found for student
                }

                $invoice = new Invoice();
                $invoice->school_username = $school_username;
                $invoice->enroll_id = $enroll->id;
                $invoice->invoice_group =$enroll->sessionyear_id."-".$enroll->programyear_id."-".$enroll->level_id
                         ."-".$enroll->faculty_id."-".$enroll->department_id."-".$enroll->section_id;
                $invoice->fee_id = $fee_id;
                $invoice->fee_type = $fee_list->fee_type;
                $invoice->amount = $fee_list->amount;
                $invoice->desc = $fee_list->desc;
                $invoice->total_amount = $fee_list->amount;
                $invoice->created_by = $user_auth->id;
                $invoice->save();

                $createdCount++;
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Data added successfully',
            'created_invoices' => $createdCount,
        ], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to add data',
            'error' => $e->getMessage(),
        ], 500);
    }
  }


    }


