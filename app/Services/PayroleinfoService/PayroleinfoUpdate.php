<?php
namespace App\Services\PayroleinfoService;

use App\Models\Payroleinfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;


class PayroleinfoUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {

            $user_auth =user();
            $model = Payroleinfo::findOrFail($id);

            $validator = validator($request->all(), [
              
                'basic_salary' => 'required|numeric',
                'college_sallary' => 'required|numeric',
                
            ]);

            

         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

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


          $model->updated_by = $user_auth->id; 
          
            $model->save();

            DB::commit();

            return response()->json([
                'message' => 'Data updated successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update school',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   

}
