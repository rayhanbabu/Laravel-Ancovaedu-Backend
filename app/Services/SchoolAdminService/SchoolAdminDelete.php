<?php
namespace App\Services\SchoolAdminService;

use App\Models\Agent;
use App\Models\User;
use App\Models\School;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SchoolAdminDelete
{
    public function handle(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $school = School::findOrFail($id);
          
            $user = User::findOrFail($school->user_id);
            $this->deleteImage($user->profile_picture);

              // Delete user role
              User_role::where('user_id', $school->user_id)->delete();

            // Delete user school
            $school->delete();

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
