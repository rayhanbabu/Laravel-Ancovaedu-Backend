<?php
namespace App\Services\PageService;

use App\Models\Page;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class PageDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
            $Page = Page::findOrFail($id);
            $this->deleteImage($Page->image);
            $Page->delete();

         
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
