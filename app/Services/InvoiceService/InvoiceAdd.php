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


              $enrollments = Enroll::where([
                    ['school_username', $school_username],
                    ['sessionyear_id', $fee->sessionyear_id],
                    ['programyear_id', $fee->programyear_id],
                    ['level_id', $fee->level_id],
                    ['faculty_id', $fee->faculty_id],
                    ['department_id', $fee->department_id],
                    ['section_id', $fee->section_id],
                ])->get();


            if ($enrollments->isEmpty()) {
                  return response()->json([
                        'message' => 'No students found for the given criteria',
                    ], 400);
             }


               $query = Invoice::query();
                    $query->where('school_username', $school_username)
                        ->where('fee_id', $fee->id)
                        ->with('enroll')
                        ->whereHas('enroll', function ($q) use ($request,$fee) {
                            $q->where('sessionyear_id', $fee->sessionyear_id)
                                ->where('programyear_id', $fee->programyear_id)
                                ->where('level_id', $fee->level_id)
                                ->where('faculty_id', $fee->faculty_id)
                                ->where('department_id', $fee->department_id)
                                ->where('section_id', $fee->section_id);
                        });

                $invoice = $query->exists();

              
            if ($invoice) {
                return response()->json([
                    'message' => 'Invoice already exists for the given criteria',
                ], 400);
            }else{

               

         foreach ($enrollments as $enrollment) {
                $Invoice = new Invoice();
                $Invoice->school_username = $school_username;
                $Invoice->invoice_group=$enrollment->sessionyear_id."-".$enrollment->programyear_id."-".$enrollment->level_id
                         ."-".$enrollment->faculty_id."-".$enrollment->department_id."-".$enrollment->section_id;
                $Invoice->enroll_id = $enrollment->id;
                $Invoice->fee_id = $request->fee_id;
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
