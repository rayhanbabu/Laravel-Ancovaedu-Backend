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
    
            $marksCreated = 0;
            foreach ($enrollments as $enroll) {
                foreach ($subjects as $subject) {
                   //  Assign Fixed subjects to all
                    if ($subject->subject_category === "Fixed") {
                        if (!Mark::where('enroll_id', $enroll->id)->where('subject_id', $subject->id)->exists()) {
                            $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                            $marksCreated++;
                        }
                    }
    
                    // Assign Religion-based subjects if student's religion matches
                    if (
                        $subject->subject_category === "Religion" &&
                        $enroll->student->religion_id == $subject->religion_id
                    ) {
                        if (!Mark::where('enroll_id', $enroll->id)->where('subject_id', $subject->id)->exists()) {
                            $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                            $marksCreated++;
                        }
                    }


                    if (
                        $subject->subject_category === "Dynamic" &&
                        in_array($subject->id, [
                            $enroll->main_subject1,
                            $enroll->main_subject2,
                            $enroll->main_subject3,
                            $enroll->additional_subject
                        ])
                    ) {
                        if (!Mark::where('enroll_id', $enroll->id)->where('subject_id', $subject->id)->exists()) {
                            $this->createMark($enroll, $subject, $request, $school_username, $user_auth);
                            $marksCreated++;
                        }
                    }

                }
    
                // Confirm enrollment
                $enroll->update(['subject_created_by'=>$user_auth->id]);
            }
    
            DB::commit();
    
            return response()->json([
                'message'                     => 'Students enrollment final submission completed.',
                'total_enrollments_processed' => $enrollments->count(),
                'total_subjects_assigned'     => $subjects->count(),
                'total_marks_created'         => $marksCreated,
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
       'enroll_id'       => $enroll->id,
       'subject_id'      => $subject->id,
       'mark_group'      => $enroll->sessionyear_id."-".$enroll->programyear_id."-".$enroll->level_id
                         ."-".$enroll->faculty_id."-".$enroll->department_id."-".$enroll->section_id."-".$request->exam_id
                         ."-".$subject->id,
      'school_username' => $school_username,
      'exam_id'         => $request->exam_id,
      'created_by'      => $user_auth->id,
   ]);
    }
    
  

}
