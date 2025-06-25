<?php
namespace App\Services\BalanceService;

use App\Models\Agent;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BalanceDelete
{
    public function handle(Request $request ,$school_username, $id)
    {
        DB::beginTransaction();
        try {

             $latestBalance = Balance::where('school_username', $school_username)->where('status', 1)
             ->orderBy('updated_at', 'desc')
             ->first();
          $model = Balance::findOrFail($id);
          if ($latestBalance && $latestBalance->id === $id) {
               $this->deleteImage($model->image);
               // Delete agent and user
                $model->delete();

               DB::commit();
            return response()->json([
                'message' => 'Balance deleted successfully',
            ], 200);

           }else if($model->status == 0){
                 $this->deleteImage($model->image);
                // Delete agent and user
                 $model->delete();

                  DB::commit();
                 return response()->json([
                  'message' => 'Balance deleted successfully',
               ], 200);
           }
           
           else{
               return response()->json([
                    'message' => 'Only the latest active balance can be Deleted.',
                    'latest_id' => $latestBalance ? $latestBalance->id : null,
                ], 400);
            }

          
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function deleteImage($image)
    {
        if ($image) {
            $path = public_path('uploads/admin') . '/' . $image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }


}
