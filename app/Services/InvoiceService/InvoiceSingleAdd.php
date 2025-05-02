<?php
namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Fee;
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
                 'fee_id'        => 'required|array',
                 'fee_id.*'      => 'integer|exists:fees,id',

            ]);
            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }


            $fee_ids = $request->fee_id;
            $fee_ids = is_array($fee_ids) ? $fee_ids : [$fee_ids];
            $fee_ids = array_unique($fee_ids); // Remove duplicates
            $fee_ids = array_filter($fee_ids); // Remove null values


            foreach ($fee_ids as $fee_id) {
                $exists = Invoice::where([
                    ['school_username', $school_username],
                    ['sessionyear_id', $request->sessionyear_id],
                    ['programyear_id', $request->programyear_id],
                    ['level_id', $request->level_id],
                    ['faculty_id', $request->faculty_id],
                    ['department_id', $request->department_id],
                    ['section_id', $request->section_id],
                    ['student_id', $request->student_id],
                    ['fee_id', $fee_id],
                ])->exists();
                    if ($exists) {
                                   
                    } else {
                        $fee_list= Fee::find($fee_id);
                        $invoice = new Invoice();
                        $invoice->school_username = $school_username;
                        $invoice->sessionyear_id = $request->sessionyear_id;
                        $invoice->programyear_id = $request->programyear_id;
                        $invoice->level_id = $request->level_id;
                        $invoice->faculty_id = $request->faculty_id;
                        $invoice->department_id = $request->department_id;
                        $invoice->section_id = $request->section_id;
                        $invoice->student_id = $request->student_id;

                        $invoice->fee_id = $fee_id;
                        $invoice->fee_type = $fee_list->fee_type;
                        $invoice->amount = $fee_list->amount;
                        $invoice->desc = $fee_list->desc;
                        $invoice->total_amount = $fee_list->amount;   
                        $invoice->created_by = $user_auth->id;               
                        $invoice->save();
                    }
                }
           
            DB::commit();

            return response()->json([
                'message' => 'Data SingleAddd successfully',
                'data' => $request->all(),
            ], 200);

         } catch (\Exception $e) {
             DB::rollback();
              return response()->json([
                  'status' => 'error',
                  'message' => 'Failed to SingleAdd school',
                  'error' => $e->getMessage(),
              ], 500);
         }
    }


}
