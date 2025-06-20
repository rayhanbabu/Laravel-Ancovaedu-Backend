<?php
namespace App\Services\GpaListService;

use App\Models\Gpalist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GpaListDelete
{
    public function handle(Request $request, $school_username, $gpa_list_id)
    {
        DB::beginTransaction();
        try {
            $gpaList = Gpalist::findOrFail($gpa_list_id);
            $gpaList->delete();


            DB::commit();
            return response()->json([
                'message' => 'GPA List deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete GPA List',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
