<?php
namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Fee;
use App\Models\Enroll;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class InvoiceCustomAdd
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
                 'amount' => 'required|integer',
                 'desc' => 'required|string',  
                 'fee_type' => 'required|string',                
            ]);
            

           if ($validator->fails()) {
                  return response()->json([
                      'message' => 'Validation failed',
                      'errors' => $validator->errors(),
                   ], 422);
               }


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
                  return response()->json([
                        'message' => 'No students found for the given criteria',
                    ], 400);
                }    


                        $invoice = new Invoice();
                        $invoice->school_username = $school_username;
                        $invoice->enroll_id = $enroll->id;
                        $invoice->fee_type = $request->fee_type;
                        $invoice->amount = $request->amount;
                        $invoice->desc = $request->desc;
                        $invoice->total_amount = $request->amount;   
                        $invoice->created_by = $user_auth->id;               
                        $invoice->save();
                    
                
            DB::commit();

            return response()->json([
                'message' => 'Data Single Addd successfully',
                'data' => $invoice,
            ], 200);

         } catch (\Exception $e) {
             DB::rollback();
              return response()->json([
                   'status' => 'error',
                   'message' => 'Failed to single add school',
                   'error' => $e->getMessage(),
              ], 500);
         }
    }


}
