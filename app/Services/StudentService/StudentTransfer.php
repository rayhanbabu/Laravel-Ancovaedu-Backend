<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class StudentTransfer
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();

        try {
            $user = user();

            // Validation rules
            $rules = [
                'sessionyear_id'    => 'required|integer|exists:sessionyears,id',
                'programyear_id'    => 'required|integer|exists:programyears,id',
                'level_id'          => 'required|integer|exists:levels,id',
                'faculty_id'        => 'required|integer|exists:faculties,id',
                'department_id'     => 'required|integer|exists:departments,id',
                'section_id'        => 'required|integer|exists:sections,id',
                'to_sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'to_programyear_id' => 'required|integer|exists:programyears,id',
                'to_level_id'       => 'required|integer|exists:levels,id',
                'to_faculty_id'     => 'required|integer|exists:faculties,id',
                'to_department_id'  => 'required|integer|exists:departments,id',
                'to_section_id'     => 'required|integer|exists:sections,id',
            ];

            // Validate request
            $validator = validator($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Fetch enrolled students based on given criteria
            $enrollments = Enroll::where([
                    ['school_username', $school_username],
                    ['sessionyear_id', $request->sessionyear_id],
                    ['programyear_id', $request->programyear_id],
                    ['level_id', $request->level_id],
                    ['faculty_id', $request->faculty_id],
                    ['department_id', $request->department_id],
                    ['section_id', $request->section_id],
                ])->get();

            if ($enrollments->isEmpty()) {
                return response()->json([
                    'message' => 'No students found for the given criteria',
                ], 404);
            }


              $enroll_group = $request->to_sessionyear_id."-".$request->to_programyear_id."-".$request->to_level_id
                 ."-".$request->to_faculty_id."-".$request->to_department_id."-".$request->to_section_id;

            $countCreated = 0;
            foreach ($enrollments as $enrollment) {
                // Check for existing enrollment in target session
                $exists = Enroll::where([
                        ['school_username', $school_username],
                        ['student_id', $enrollment->student_id],
                        ['sessionyear_id', $request->to_sessionyear_id],
                        ['programyear_id', $request->to_programyear_id],
                        ['level_id', $request->to_level_id],
                        ['faculty_id', $request->to_faculty_id],
                        ['department_id', $request->to_department_id],
                        ['section_id', $request->to_section_id],
                    ])->exists();

                if ($exists) {
                    // return response()->json([
                    //     'message' => 'A student already exists in the target session',
                    // ], 400);
                }else{
                // Create new enrollment for target session
                 Enroll::create([
                    'user_id'         => $enrollment->user_id,
                    'student_id'      => $enrollment->student_id,
                    'school_username' => $enrollment->school_username,
                    'roll'            => $enrollment->roll,

                    'sessionyear_id'    => $request->to_sessionyear_id,
                    'programyear_id'    => $request->to_programyear_id,
                    'level_id'          => $request->to_level_id,
                    'faculty_id'        => $request->to_faculty_id,
                    'department_id'     => $request->to_department_id,
                    'section_id'        => $request->to_section_id,
                    'enroll_group'      => $enroll_group,

                    'created_by'        => $user->id,
                 ]);

                 $countCreated++; // increment counter

              }
            }

            DB::commit();

            return response()->json([
                 'message' => "$countCreated student enrolled successfully.",
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to transfer students',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

   
  

}
