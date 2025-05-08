<?php
namespace App\Services\StudentService;

use App\Models\School;
use App\Models\Enroll;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class StudentMark
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();
    
        try {
            $user_auth = user();
    
            // Validation rules
            $rules = [
                'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'programyear_id' => 'required|integer|exists:programyears,id',
                'level_id'       => 'required|integer|exists:levels,id',
                'faculty_id'     => 'required|integer|exists:faculties,id',
                'department_id'  => 'required|integer|exists:departments,id',
                'section_id'     => 'required|integer|exists:sections,id',
                'exam_id'        => 'required|integer|exists:exams,id',
            ];
    
            // Validate request
            $validator = validator($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }
    
            // Fetch enrollments
            $enrollments = Enroll::with('student')->where([
                ['school_username', $school_username],
                ['sessionyear_id', $request->sessionyear_id],
                ['programyear_id', $request->programyear_id],
                ['level_id', $request->level_id],
                ['faculty_id', $request->faculty_id],
                ['department_id', $request->department_id],
                ['section_id', $request->section_id],
                ['confirm_enroll_status', 0],
            ])->get();
    
            if ($enrollments->isEmpty()) {
                return response()->json([
                    'message' => 'No unconfirmed enrollments found.',
                ], 404);
            }
    
            // Fetch all possible subjects
            $subjects = Subject::where([
                ['school_username', $school_username],
                ['sessionyear_id', $request->sessionyear_id],
                ['programyear_id', $request->programyear_id],
                ['level_id', $request->level_id],
                ['faculty_id', $request->faculty_id],
                ['department_id', $request->department_id],
            ])->get();
    
            if ($subjects->isEmpty()) {
                return response()->json([
                    'message' => 'No subjects found for the given criteria.',
                ], 404);
            }
    
            foreach ($enrollments as $enroll) {
                foreach ($subjects as $subject) {
                    // Assign Fixed subjects to all
                    if ($subject->subject_category === "Fixed") {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }
    
                    // Assign Religion-based subjects if student's religion matches
                    if (
                        $subject->subject_category === "Religion" &&
                        $enroll->student->religion_id == $subject->religion_id
                    ) {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }


                    // Assign Main Subject -based subjects if student's relSubject Id igion matches
                       if (
                        $subject->subject_category === "Dynamic" &&
                        $enroll->main_subject1 == $subject->id
                    ) {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }

                    // Main Subject 2
                    if (
                        $subject->subject_category === "Dynamic" &&
                        $enroll->main_subject2 == $subject->id
                    ) {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }

                   // Main SUbejcet 3
                    if (
                        $subject->subject_category === "Dynamic" &&
                        $enroll->main_subject3 == $subject->id
                    ) {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }

                   // Additinal Subject
                    if (
                        $subject->subject_category === "Dynamic" &&
                        $enroll->additional_subject == $subject->id
                    ) {
                        $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                    }

                }
    
                // Confirm enrollment
                $enroll->update(['confirm_enroll_status' => 1]);
            }
    
            DB::commit();
    
            return response()->json([
                'message'                     => 'Students enrollment final submission completed.',
                'total_enrollments_processed' => $enrollments->count(),
                'total_subjects_assigned'     => $subjects->count(),
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to process enrollments',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Create a Mark record for a student and subject.
     */
    protected function createMark($enroll, $subject, $request, $school_username, $user_auth)
    {
        Mark::create([
            'student_id'      => $enroll->student_id,
            'subject_id'      => $subject->id,
            'school_username' => $school_username,
            'sessionyear_id'  => $request->sessionyear_id,
            'programyear_id'  => $request->programyear_id,
            'level_id'        => $request->level_id,
            'faculty_id'      => $request->faculty_id,
            'department_id'   => $request->department_id,
            'section_id'      => $request->section_id,
            'exam_id'         => $request->exam_id,
            'created_by'      => $user_auth->id,
        ]);
    }
    
  

}
