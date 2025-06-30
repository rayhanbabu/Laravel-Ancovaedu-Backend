<?php
namespace App\Services\MarkService;

use App\Models\Mark;
use App\Models\Classdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\FinalResult;
use App\Models\Markinfo;

class MarkSubmit
{
    public function handle(Request $request, $school_username)
    {
      

       DB::beginTransaction();
    try {
        $final_submit_status = $request->final_submit_status;
        $user_auth = user();

        // Update Mark final_submit_status for matching enrollments
        $query = Mark::where('school_username', $school_username)
            ->where('exam_id', $request->exam_id)
            ->where('subject_id', $request->subject_id)
            ->with('enroll')
            ->whereHas('enroll', function ($q) use ($request) {
                $q->where('sessionyear_id', $request->sessionyear_id)
                  ->where('programyear_id', $request->programyear_id)
                  ->where('level_id', $request->level_id)
                  ->where('faculty_id', $request->faculty_id)
                  ->where('department_id', $request->department_id);

                if ($request->has('section_id')) {
                    $q->where('section_id', $request->section_id);
                }
            });

        $affectedRows = $query->update([
            'final_submit_status' => $final_submit_status,
            'final_submited_by'   => $user_auth->id,
        ]);

        // Fetch grading ranges for GPA mapping
        $markinfo = Markinfo::where([
                ['school_username', $school_username],
                ['sessionyear_id', $request->sessionyear_id],
                ['programyear_id', $request->programyear_id],
                ['level_id', $request->level_id],
                ['faculty_id', $request->faculty_id],
                ['department_id', $request->department_id],
            ])
            ->orderBy('gpa', 'asc')
            ->get();

        // Aggregate result per student
        $result = Mark::join('enrolls', 'marks.enroll_id', '=', 'enrolls.id')
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->where('marks.school_username', $school_username)
            ->where('marks.exam_id', $request->exam_id)
            ->where('enrolls.sessionyear_id', $request->sessionyear_id)
            ->where('enrolls.programyear_id', $request->programyear_id)
            ->where('enrolls.level_id', $request->level_id)
            ->where('enrolls.faculty_id', $request->faculty_id)
            ->where('enrolls.department_id', $request->department_id)
            ->where('enrolls.section_id', $request->section_id)
            ->with('exam:id,exam_name', 'student')
            ->select(
                'enrolls.student_id',
                DB::raw('COUNT(marks.subject_id) as total_subject'),
                DB::raw('MAX(marks.exam_id) as exam_id'),
                DB::raw('SUM(CASE WHEN marks.gpa >= 1.00 THEN 1 ELSE 0 END) as total_pass'),
                DB::raw('SUM(marks.attendance_status) as total_attendance'),
                DB::raw('MAX(enrolls.sessionyear_id) as sessionyear_id'),
                DB::raw('MAX(enrolls.programyear_id) as programyear_id'),
                DB::raw('MAX(enrolls.level_id) as level_id'),
                DB::raw('MAX(enrolls.faculty_id) as faculty_id'),
                DB::raw('MAX(enrolls.department_id) as department_id'),
                DB::raw('MAX(enrolls.section_id) as section_id'),
                DB::raw('MAX(enrolls.roll) as roll'),
                DB::raw('MAX(marks.enroll_id) as enroll_id'),
                DB::raw('SUM(marks.total) as total_mark'),
                DB::raw('SUM(marks.gpa) as gpa_total'),
                DB::raw('ROUND(SUM(marks.gpa) / COUNT(marks.subject_id), 2) as gpa'),
                DB::raw('RANK() OVER (ORDER BY ROUND(SUM(marks.gpa) / COUNT(marks.subject_id), 2) DESC, SUM(marks.total) DESC) as merit_position')
            )
            ->groupBy('enrolls.student_id')
            ->get();

        // Insert or update FinalResult per student
        foreach ($result as $student) {
            $finalResultData = [
                'student_id'             => $student->student_id,
                'school_username'        => $school_username,
                'enroll_id'              => $student->enroll_id,
                'finalresult_group'      => "{$student->sessionyear_id}-{$student->programyear_id}-{$student->level_id}-{$student->faculty_id}-{$student->department_id}-{$student->section_id}-{$student->exam_id}",
                'exam_id'                => $student->exam_id,
                'total_subject'          => $student->total_subject,
                'total_subject_passed'   => $student->total_pass,
                'total_subject_failed'   => $student->total_subject - $student->total_pass,
                'merit_position'         => $student->merit_position,
                'total_attendance'       => $student->total_attendance,
                'total_mark'             => $student->total_mark,
                'gpa_total'              => $student->gpa_total,
                'gpa'                    => $student->gpa,
                'grade'                  => $this->calculateGrade($markinfo, $student->gpa),
            ];

            FinalResult::updateOrCreate(
                [
                    'enroll_id'       => $student->enroll_id,
                    'exam_id'         => $student->exam_id,
                    'school_username' => $school_username
                ],
                $finalResultData
            );
        }

        DB::commit();
        return response()->json([
            'message'        => 'Final Submitted Status successfully',
            'affected_rows'  => $affectedRows,
            'students'       => $result,
            'markinfo'       => $markinfo,
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Failed to Submit agent',
            'error'   => $e->getMessage(),
        ], 500);
    }

}

     public function calculateGrade( $markinfo, $gpa)
                {
                   foreach ($markinfo as $item) {
                      if ($gpa >= $item->gpa && $gpa < $item->gparange) {
                            return $item->grade;
                      }
                      }

                    return "F";
                }

}
