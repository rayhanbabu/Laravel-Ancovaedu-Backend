<?php

namespace App\Services\PayroleinfoService;

use App\Models\Payroleinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class PayroleinfoAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'employee_id' => [
                    'required',
                    Rule::exists('employees', 'id'),
                    Rule::unique('payroleinfos', 'employee_id'),
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

    
            $user_auth =user();
        
            $model = new Payroleinfo();
            $model->employee_id = $request->employee_id;

            $model->basic_salary = $request->basic_salary;
            $model->college_sallary = $request->college_sallary;
            $model->increment = $request->increment;
            $model->city = $request->city;
            $model->medical = $request->medical;
            $model->house_rent = $request->house_rent;
            $model->contributory = $request->contributory;
            $model->incentive = $request->incentive;
            $model->arrear = $request->arrear;
            $model->other = $request->other;


            $model->loan_refund = $request->loan_refund;
            $model->attendance = $request->attendance;
            $model->tax = $request->tax;
            $model->gratuity_loan = $request->gratuity_loan;

            $model->boishakhi = $request->boishakhi;
            $model->adha = $request->adha;
            $model->fitr = $request->fitr;


            $model->created_by = $user_auth->id; 
            $model->school_username = $school_username;
            $model->save();

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
