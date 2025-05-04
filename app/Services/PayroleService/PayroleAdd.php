<?php

namespace App\Services\PayroleService;

use App\Models\Payrole;
use App\Models\Payroleinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class PayroleAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
               
                'payrole_date' => 'required|date|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user_auth = user();
            $date = $request->payrole_date;
            $month= date('m', strtotime($date));
            $year= date('Y', strtotime($date));

    
          $payroleinfo=Payroleinfo::where('school_username',$school_username)->get();
            if($payroleinfo->isEmpty()){
                return response()->json([
                    'message' => 'Payrole info not found',
                ], 400);
            }

            $payrole=Payrole::where('school_username',$school_username)->where('month',$month)->where('year',$year)->get();
            if($payrole->isNotEmpty()){
                return response()->json([
                    'message' => 'Payrole already exists for this month',
                ], 400);

            }else{
                foreach($payroleinfo as $info){
                    $model = new Payrole();
                    $model->employee_id = $info->employee_id;
                    $model->payroleinfo_id = $info->id;
                    $model->basic_salary = $info->basic_salary;
                    $model->college_sallary = $info->college_sallary;
                    $model->increment = $info->increment;
                    $model->city = $info->city;
                    $model->medical = $info->medical;
                    $model->house_rent = $info->house_rent;
                    $model->contributory = $info->contributory;
                    $model->incentive = $info->incentive;
                    $model->arrear = $info->arrear;
                    $model->other = $info->other;

                    $model->total_gross= $info->basic_salary + $info->college_sallary + $info->increment 
                              + $info->city + $info->medical + $info->house_rent + $info->contributory 
                              + $info->incentive + $info->arrear + $info->other;

                    $model->loan_refund = $info->loan_refund;
                    $model->attendance = $info->attendance;
                    $model->tax = $info->tax;
                    $model->gratuity_loan = $info->gratuity_loan;

                    $model->total_deduction = $info->loan_refund+$info->attendance + $info->tax + $info->gratuity_loan;

                    $model->total_net=$model->total_gross-$model->total_deduction;

                    $model->boishakhi = $info->boishakhi;
                    $model->adha = $info->adha;
                    $model->fitr = $info->fitr;

                   

                    // Add the month and year to the model
                    $model->month = $month;
                    $model->year = $year;
                    $model->payrole_date = $date;

                    // Save the model
                    $model->created_by = $user_auth->id;
                    $model->school_username = $school_username;
                    $model->save();
               }
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
