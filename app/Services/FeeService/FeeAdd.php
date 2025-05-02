<?php

namespace App\Services\FeeService;

use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class FeeAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                 'desc' => 'required',
                 'amount' => 'required|integer',
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',              
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

        

        
            $fee = new Fee();
            $fee->school_username = $request->school_username;
            $fee->sessionyear_id = $request->sessionyear_id;
            $fee->programyear_id = $request->programyear_id;
            $fee->level_id = $request->level_id;
            $fee->faculty_id = $request->faculty_id;
            $fee->department_id = $request->department_id;
            $fee->section_id = $request->section_id;
            $fee->desc = $request->desc;
            $fee->amount = $request->amount;
            $fee->fee_type = $request->fee_type;
            $fee->created_by = $user_auth->id;
            $fee->save();


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
