<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Student;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class StudentGroupDelete
{


   public function handle($request, $school_username)
   {

       DB::beginTransaction();

    try {
        $user_auth = user();

        $rules = [
            'sessionyear_id' => 'required|integer|exists:sessionyears,id',
            'programyear_id' => 'required|integer|exists:programyears,id',
            'level_id'       => 'required|integer|exists:levels,id',
            'faculty_id'     => 'required|integer|exists:faculties,id',
            'department_id'  => 'required|integer|exists:departments,id',
            'section_id'     => 'required|integer|exists:sections,id',
        ];

        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Fetch enrollments
        $enrollments = Enroll::where([
            ['school_username', $school_username],
            ['sessionyear_id', $request->sessionyear_id],
            ['programyear_id', $request->programyear_id],
            ['level_id', $request->level_id],
            ['faculty_id', $request->faculty_id],
            ['department_id', $request->department_id],
            ['section_id', $request->section_id],
            ['created_type', 'Enroll'],
        ])->get();

        if ($enrollments->count() > 0) {

            
            $studentIds = $enrollments->pluck('student_id')->toArray();

            
            $userIds = Student::whereIn('id', $studentIds)->pluck('user_id')->toArray();

            
            $deletedEnrollmentsCount = Enroll::whereIn('id', $enrollments->pluck('id'))->delete();

            
            $deletedStudentsCount = Student::whereIn('id', $studentIds)->delete();

           
            $deletedUserRolesCount = User_role::whereIn('user_id', $userIds)->delete();

          
            $deletedUsersCount = User::whereIn('id', $userIds)->delete();

            DB::commit();

            return response()->json([
                'message'                    => 'Data deleted successfully.',
                'total_enrollments_deleted'  => $deletedEnrollmentsCount,
                'total_students_deleted'     => $deletedStudentsCount,
                'total_user_roles_deleted'   => $deletedUserRolesCount,
                'total_users_deleted'        => $deletedUsersCount,
            ], 200);

        } else {
            DB::commit();
            return response()->json([
                'message' => 'Enroll Submit Confirm cannot be deleted.',
            ], 400);
        }

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to process enrollments',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    
  

}
