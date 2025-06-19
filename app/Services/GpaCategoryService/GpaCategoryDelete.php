<?php
namespace App\Services\GpaCategoryService;

use App\Models\GpaCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GpaCategoryDelete
{
    public function handle(Request $request, $school_username, $gpa_category_id)
    {
        DB::beginTransaction();
        try {
            $gpaCategory = GpaCategory::findOrFail($gpa_category_id);
            $gpaCategory->delete();

            // Delete agent and user
            $gpaCategory->delete();

            DB::commit();
            return response()->json([
                'message' => 'GPA Category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete GPA Category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
