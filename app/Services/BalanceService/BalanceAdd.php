<?php

namespace App\Services\BalanceService;

use App\Models\Category;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class BalanceAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {

            $user_auth = user();
            $school_username = $request->school_username;

            $validator = validator($request->all(), [
                'comment' => 'nullable',
                'category_id' => 'required|integer|exists:categories,id',
                'amount' => 'required|integer',
                'image' => 'image|mimes:jpeg,png,jpg,pdf|max:700',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }


            $category=Category::where('school_username',$school_username)->find($request->category_id);
            $category_type=$category->category_type;


            $model = new Balance();
            $model->school_username = $school_username;
            $model->comment = $request->comment;
            $model->category_id = $request->category_id;
            $model->amount = $request->amount;
            $model->category_type = $category_type;
            $model->date = date('Y-m-d');
            $model->year = date('Y');
            $model->month = date('m');
            $model->day = date('d'); 
            $model->balance = 0;
            $model->status = 0;
            $model->created_by = $user_auth->id;

            if ($request->hasfile('image')) {
                $model->image = $this->uploadFile($request->file('image'), 'image');
            }

            $model->save();

           

            DB::commit();

            return response()->json([
                  'message' => 'Data  added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

    private function uploadFile($file, $prefix)
    {
        $fileName = $prefix . rand() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/admin'), $fileName);
        return $fileName;
    }
}
