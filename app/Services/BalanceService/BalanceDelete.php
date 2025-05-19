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
            $model = Balance::findOrFail($id);
            $this->deleteImage($model->image);

            // Delete agent and user
            $model->delete();

            DB::commit();
            return response()->json([
                'message' => 'Balance deleted successfully',
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
