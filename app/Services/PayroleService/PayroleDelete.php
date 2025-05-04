<?php
namespace App\Services\PayroleService;

use App\Models\Payrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PayroleDelete
{
    public function handle(Request $request,$school_username, $id)
    {
        DB::beginTransaction();
        try {
            $model = Payrole::findOrFail($id); 

            if($model->salary_status==1){

                return response()->json([
                    'message' => 'Salary status aleardy paid',
                ], 200);
            }
            // Delete agent and user
            $model->delete();

            DB::commit();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
