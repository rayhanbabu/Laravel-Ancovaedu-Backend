<?php
namespace App\Services\EmployeeService;

use App\Models\Employee;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EmployeeDelete
{
    public function handle(Request $request, $school_username, $employee_id)
    {
        DB::beginTransaction();
        try {
            $Employee = Employee::findOrFail($employee_id);

            $user = User::findOrFail($Employee->user_id);
            $this->deleteImage($user->profile_picture);

            // Delete user role
            User_role::where('user_id', $Employee->user_id)->delete();

            // Delete agent and user
            $Employee->delete();
            $user->delete();

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
