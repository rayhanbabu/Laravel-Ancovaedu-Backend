<?php
namespace App\Services\ManagerService;

use App\Models\Agent;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ManagerDelete
{
    public function handle(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $role = User_role::findOrFail($id);
          
            $user = User::findOrFail($role->user_id);
            $this->deleteImage($user->profile_picture);

            // Delete user role
            $role->delete();

            // Delete agent and user
            $user->delete();

            DB::commit();
            return response()->json([
                'message' => 'Manager deleted successfully',
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
