<?php
namespace App\Services\StudentService;

use App\Models\Student;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StudentDelete
{
    public function handle(Request $request, $school_username, $student_id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($student_id);

         

            $user = User::findOrFail($student->user_id);
            $this->deleteImage($user->profile_picture);

            // Delete user role
            User_role::where('user_id', $student->user_id)->delete();

            // Delete agent and user
            $student->delete();
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
