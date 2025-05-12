<?php
namespace App\Services\PermissionService;

use App\Models\EmployeePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PermissionDelete
{
    public function handle(Request $request, $school_username, $id)
    {
        DB::beginTransaction();
        try {
             $Permission = EmployeePermission::findOrFail($id);
             $Permission->delete();
           
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
