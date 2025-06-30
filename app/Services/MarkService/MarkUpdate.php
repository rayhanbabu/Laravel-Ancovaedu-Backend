<?php
namespace App\Services\MarkService;


use App\Models\Mark;
use App\Models\Markinfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exception;

class MarkUpdate
{
    public function handle($request, $school_username)
    {
        DB::beginTransaction();
        try {
            $user_auth = user();
    
            // Validate input
            $validator = validator($request->all(), [
                'id'          => 'required|integer|exists:marks,id',
                'field_mark'  => 'required|numeric',
                'field_name'  => 'required|string'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }
    
            // Allowed fields
            $allowedFields = ['level1_mark', 'level2_mark', 'level3_mark'];
            if (!in_array($request->field_name, $allowedFields)) {
                return response()->json([
                    'message' => 'Invalid field name',
                ], 400);
            }
    
            // Find mark record
            $mark = Mark::with('subject')->find($request->id);
            if (!$mark) {
                return response()->json([
                    'message' => 'Mark not found',
                ], 400);
            }

            if ($mark->final_submit_status > 0) {
                return response()->json([
                    'message' => 'Final submission is already done. You cannot input marks.',
                ], 400);
            }

            // Fetch markinfo
            $markinfo = Markinfo::where([
                ['school_username', $school_username],
                ['sessionyear_id', $mark->enroll->sessionyear_id],
                ['programyear_id', $mark->enroll->programyear_id],
                ['level_id', $mark->enroll->level_id],
                ['faculty_id', $mark->enroll->faculty_id],
                ['department_id', $mark->enroll->department_id],
            ])->get();
    
            // Map of max limits for each mark level
            $limitMap = [
                'level1_mark' => $mark->subject->input_number1,
                'level2_mark' => $mark->subject->input_number2,
                'level3_mark' => $mark->subject->input_number3,
            ];
    
            $fieldName   = $request->field_name;
            $fieldValue  = (float) $request->field_mark;
            $maxLimit    = (float) $limitMap[$fieldName];
    
            // Check max limit
            if ($fieldValue > $maxLimit) {
                return response()->json([
                    'message' => ucfirst(str_replace('_', ' ', $fieldName)) . " cannot exceed {$maxLimit}",
                    'mark'    => $fieldValue,
                ], 400);
            }
    
            // Update the mark field
            $mark->{$fieldName} = $fieldValue;
    
            // Recalculate total
            $mark->sub_total = $mark->level1_mark + $mark->level2_mark + $mark->level3_mark;

            $total=$mark->sub_total;

            $mark->total=$total;

            if($mark->subject->subject_type=="Combined"){
                   $combined_subject=Mark::where('enroll_id',$mark->enroll_id)
                   ->where('exam_id',$mark->exam_id)
                   ->where('subject_id',$mark->subject->combined_subject_id)->first();

                   $mark->total=$total+$combined_subject->sub_total;    
             }else{
                $mark->total=$mark->sub_total;
             }
                  
            
             
            
            // Calculate GPA & Grade
            $mark->gpa = $this->calculateGpa(
                $mark->level1_mark, $mark->subject->pass_number1,
                $mark->level2_mark, $mark->subject->pass_number2,
                $mark->level3_mark, $mark->subject->pass_number3,
                $mark->total, $markinfo, $mark->subject->total_number
            );
    
            $mark->grade = $this->calculateGrade(
                $mark->level1_mark, $mark->subject->pass_number1,
                $mark->level2_mark, $mark->subject->pass_number2,
                $mark->level3_mark, $mark->subject->pass_number3,
                $mark->total, $markinfo, $mark->subject->total_number
            );
    
            $mark->save();
    
            DB::commit();
    
            return response()->json([
                'message' => 'Data updated successfully',
                'mark'    => $mark,
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update mark',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



            public function calculateGpa($subc, $cfail, $subm, $mfail, $subp, $pfail, $total, $markinfo, $tmark)
                {
                    if ($subc < $cfail || $subm < $mfail || $subp < $pfail) {
                        return 0;
                    }

                    foreach ($markinfo as $item) {
                        $startMark = ($item->start * $tmark) / 100;
                        $endMark   = ($item->end * $tmark) / 100;

                        if ($total >= $startMark && $total < $endMark) {
                            return $item->gpa;
                        }
                    }

                    return 0;
                }

                public function calculateGrade($subc, $cfail, $subm, $mfail, $subp, $pfail, $total, $markinfo, $tmark)
                {
                    if ($subc < $cfail || $subm < $mfail || $subp < $pfail) {
                        return "F";
                    }

                    foreach ($markinfo as $item) {
                        $startMark = ($item->start * $tmark) / 100;
                        $endMark   = ($item->end * $tmark) / 100;

                        if ($total >= $startMark && $total < $endMark) {
                            return $item->grade;
                        }
                    }

                    return "F";
                }


    


        }

          // return 0;
