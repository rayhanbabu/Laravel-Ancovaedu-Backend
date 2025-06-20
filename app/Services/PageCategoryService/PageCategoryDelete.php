<?php
namespace App\Services\PageCategoryService;

use App\Models\PageCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PageCategoryDelete
{
    public function handle(Request $request, $school_username, $Page_category_id)
    {
        DB::beginTransaction();
        try {
            $PageCategory = PageCategory::findOrFail($Page_category_id);
            $PageCategory->delete();

         
            DB::commit();
            return response()->json([
                'message' => 'Page Category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete Page Category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
