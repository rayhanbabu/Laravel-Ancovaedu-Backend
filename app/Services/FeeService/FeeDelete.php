<?php
namespace App\Services\FeeService;

use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FeeDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $Fee = Fee::findOrFail($id);
             $Fee->delete();
           
            DB::commit();
            return response()->json([
                'message' => 'Agent deleted successfully',
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
