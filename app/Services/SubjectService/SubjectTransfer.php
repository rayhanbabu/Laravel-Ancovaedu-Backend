<?php
namespace App\Services\SubjectService;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class SubjectTransfer
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();

        try {
              $user_auth = user();

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
                    'message' => 'No subjects found for the given criteria',
                ], 404);
            }

              $subject_group = $request->to_sessionyear_id."-".$request->to_programyear_id."-".$request->to_level_id
                 ."-".$request->to_faculty_id."-".$request->to_department_id."-".$request->to_section_id;

              $countCreated = 0;
          
                $exists = Subject::where([
                        ['school_username', $school_username],
                        ['sessionyear_id', $request->to_sessionyear_id],
                        ['programyear_id', $request->to_programyear_id],
                        ['level_id', $request->to_level_id],
                        ['faculty_id', $request->to_faculty_id],
                        ['department_id', $request->to_department_id],
                    ])->exists();

                if ($exists) {
                    return response()->json([
                        'message' => 'Subject already exists in the target session',
                    ], 400);
                }else{

          foreach ($subjects as $row) {
                
            $subject = new Subject();
            $subject->school_username = $request->school_username;
            $subject->sessionyear_id = $request->to_sessionyear_id;
            $subject->programyear_id = $request->to_programyear_id;
            $subject->level_id = $request->to_level_id;
            $subject->faculty_id = $request->to_faculty_id;
            $subject->department_id = $request->to_department_id;
            $subject->section_id = $request->to_section_id;
            $subject->subject_group = $subject_group;
            $subject->subject_name = $row->subject_name ?? null;
            $subject->subject_code = $row->subject_code ?? null;
            $subject->serial = $row->serial ?? null;
            $subject->gpa_calculation = $row->gpa_calculation ?? null;
            $subject->input_lavel1 = $row->input_lavel1 ?? null;
            $subject->input_lavel2 = $row->input_lavel2 ?? null;
            $subject->input_lavel3 = $row->input_lavel3 ?? null;
            $subject->input_number1 = $row->input_number1 ?? null;
            $subject->input_number2 = $row->input_number2 ?? null;
            $subject->input_number3 = $row->input_number3 ?? null;
            $subject->total_number = $row->total_number ?? null;
            $subject->pass_number1 = $row->pass_number1 ?? null;
            $subject->pass_number2 = $row->pass_number2 ?? null;
            $subject->pass_number3 = $row->pass_number3 ?? null;
            $subject->subject_category = $row->subject_category ?? null;
            $subject->religion_id = $row->religion_id ?? null;
            $subject->subject_type = $row->subject_type ?? null;
            $subject->created_by = $user_auth->id;
            $subject->save();

                 $countCreated++; // increment counter

              }
            }

            DB::commit();

            return response()->json([
                 'message' => "$countCreated Subjects  Added successfully.",
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
