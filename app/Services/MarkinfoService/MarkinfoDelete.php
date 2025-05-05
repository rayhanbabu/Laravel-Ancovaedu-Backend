<?php
namespace App\Services\MarkinfoService;

use App\Models\Markinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MarkinfoDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $Markinfo = Markinfo::findOrFail($id);
             $Markinfo->delete();
           
            DB::commit();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete agent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   
}
