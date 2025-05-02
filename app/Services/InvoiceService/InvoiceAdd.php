<?php

namespace App\Services\InvoiceService;

use App\Models\Invoice;
use App\Models\Fee;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class InvoiceAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $school_username = $request->school_username;

            $validator = validator($request->all(),[     
                  'fee_id' => 'required|integer|exists:fees,id', 
             ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
              }

             $fee=Fee::find($request->fee_id);


             $exists = Invoice::where([
                ['school_username', $school_username],
                ['sessionyear_id', $fee->sessionyear_id],
                ['programyear_id', $fee->programyear_id],
                ['level_id', $fee->level_id],
                ['faculty_id', $fee->faculty_id],
                ['department_id', $fee->department_id],
                ['section_id', $fee->section_id],
                ['fee_id', $request->fee_id],                                                 
            ])->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Invoice already exists for the given criteria',
                ], 400);
            }else{

                $enrollments = Enroll::where([
                    ['school_username', $school_username],
                    ['sessionyear_id', $fee->sessionyear_id],
                    ['programyear_id', $fee->programyear_id],
                    ['level_id', $fee->level_id],
                    ['faculty_id', $fee->faculty_id],
                    ['department_id', $fee->department_id],
                    ['section_id', $fee->section_id],
                ])->get();

        foreach ($enrollments as $enrollment) {
               $Invoice = new Invoice();
                $Invoice->school_username = $school_username;
                $Invoice->sessionyear_id = $fee->sessionyear_id;
                $Invoice->student_id = $enrollment->student_id;
                $Invoice->fee_id = $request->fee_id;
                $Invoice->programyear_id = $fee->programyear_id;
                $Invoice->level_id = $fee->level_id;
                $Invoice->faculty_id = $fee->faculty_id;
                $Invoice->department_id = $fee->department_id;
                $Invoice->section_id = $fee->section_id;
                $Invoice->desc = $fee->desc;
                $Invoice->amount = $fee->amount;
                $Invoice->total_amount = $fee->amount;
                $Invoice->fee_type = $fee->fee_type;
                $Invoice->created_by = $user_auth->id;
                $Invoice->save();       
         }

            $fee->invoice_create_status = 1;
            $fee->save();


            }
            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

    
  }
