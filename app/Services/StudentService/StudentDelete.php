<?php
namespace App\Services\StudentService;

use App\Models\Student;
use App\Models\User;
use App\Models\Enroll;
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
            $user    = User::findOrFail($student->user_id);
    
            // Count enrollments by student_id
            $enrollCount = Enroll::where('student_id', $student->id)->count();
    
            if ($enrollCount > 1) {
                DB::commit(); // commit transaction if no delete is happening
                return response()->json([
                    'message'         => 'Student cannot be deleted because multiple enrollments exist.',
                    'total_enrollments' => $enrollCount,
                ], 200);
            }
    
            // Delete enrollments if any (usually 1 or 0)
            $deletedEnrollments = Enroll::where('student_id', $student->id)->delete();
    
            // Delete profile image if exists
            $this->deleteImage($user->profile_picture);
    
            // Delete user roles
            User_role::where('user_id', $student->user_id)->delete();
    
            // Delete student and user
            $student->delete();
            $user->delete();
    
            DB::commit();
            return response()->json([
                'message'                => 'student and related data deleted successfully.',
                'total_enrollments_deleted' => $deletedEnrollments,
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete agent',
                'error'   => $e->getMessage(),
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
