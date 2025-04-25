<?php
namespace App\Services\LevelService;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LevelDelete
{
    public function handle(Request $request,$school_username, $id)
    {
        DB::beginTransaction();
        try {
            $model = Level::findOrFail($id); 
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
