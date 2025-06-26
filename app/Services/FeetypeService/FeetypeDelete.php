<?php
namespace App\Services\FeetypeService;

use App\Models\Feetype;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FeetypeDelete
{
    public function handle(Request $request,$school_username, $id)
    {
        DB::beginTransaction();
        try {

            $user_auth = user();

            $data=Invoice::where('feetype_id', $id)
                ->where('school_username', $school_username)
                ->count();

                if($data > 0) {
                    return response()->json([
                        'message' => 'This feetype is used in invoice, so you can not delete it',
                    ], 400);
                }
            $model = Feetype::findOrFail($id); 
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
