<?php
namespace App\Services\BalanceService;

use App\Models\Category;
use App\Models\Balance;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class BalanceUpdate
{
    public function handle($request,$school_username, $id)
    {

        DB::beginTransaction();
        try {
            $user_auth = user();
            $model = Balance::findOrFail($id);

             $validator = validator($request->all(), [
                'comment' => 'nullable',
                'category_id' => 'required|integer|exists:categories,id',
                'amount' => 'required|integer|min:1',
                'image' => 'mimes:jpeg,png,jpg,pdf|max:700',
            ]);


         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
              ], 422);
          }

           if($model->status==1){
              return response()->json([
                 'message' => 'Payment already verified. No data updated',
               ], 400);
           }

           $category=Category::find($request->category_id);
             $category_type=$category->category_type;

            $model->comment = $request->comment;
            $model->category_id = $request->category_id;
            $model->amount = $request->amount;
            $model->category_type = $category_type;
            $model->balance = 0;
            $model->status = 0;
            $model->updated_by = $user_auth->id;

            if ($request->hasFile('image')) {
                $this->handleProfilePictureUpload($request, $model);
            }

            $model->save();
          

            DB::commit();

            return response()->json([
                'message' => 'Data updated successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
    private function handleProfilePictureUpload($request, $model)
    {
        $path = public_path('uploads/admin') . '/' . $model->image;
         if ($model->image && File::exists($path)) {
             File::delete($path);
         }
         $image = $request->file('image');
         $fileName = 'profile_picture' . rand() . '.' . $image->getClientOriginalExtension();
         $image->move(public_path('uploads/admin'), $fileName);
         $model->image = $fileName;
    }

}
