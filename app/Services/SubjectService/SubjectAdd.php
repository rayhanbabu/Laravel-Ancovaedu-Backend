<?php

namespace App\Services\SubjectService;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;

class SubjectAdd
{
    public function handle(Request $request,$school_username)
    {

        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'subject_name' => 'required',
                'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                'programyear_id' => 'required|integer|exists:programyears,id',
                'level_id' => 'required|integer|exists:levels,id',
                'faculty_id' => 'required|integer|exists:faculties,id',
                'department_id' => 'required|integer|exists:departments,id',
                'section_id' => 'required|integer|exists:sections,id',  
                'religion_id' => 'nullable|exists:religions,id',
                'combined_subject_id' => 'nullable|exists:subjects,id',
                'subject_category' => 'required',
                'subject_type' => 'required',       

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

               $subject_group = $request->sessionyear_id."-".$request->programyear_id."-".$request->level_id
                 ."-".$request->faculty_id."-".$request->department_id."-".$request->section_id;


                   $data = Subject::where([
                        ['school_username', $school_username],
                        ['sessionyear_id', $request->sessionyear_id],
                        ['programyear_id', $request->programyear_id],
                        ['level_id', $request->level_id],
                        ['faculty_id', $request->faculty_id],
                        ['department_id', $request->department_id],
                    ])->get();

                  

                if ($data->count() == 0 || $data->first()->section_id == $request->section_id) {
                    $user_auth = user();
                    $model = new Subject();
                    $model->school_username = $school_username;
                    $model->subject_name = $request->subject_name;
                    $model->sessionyear_id = $request->sessionyear_id;
                    $model->programyear_id = $request->programyear_id;
                    $model->level_id = $request->level_id;
                    $model->faculty_id = $request->faculty_id;
                    $model->department_id = $request->department_id;
                    $model->section_id = $request->section_id;
                    $model->religion_id = $request->religion_id;
                    $model->subject_category = $request->subject_category;
                    $model->subject_type = $request->subject_type;
                    $model->created_by = $user_auth->id;
                    $model->subject_code = $request->subject_code;
                    $model->input_lavel1 = $request->input_lavel1;
                    $model->input_lavel2 = $request->input_lavel2;
                    $model->input_lavel3 = $request->input_lavel3;
                    $model->input_number1 = $request->input_number1;
                    $model->input_number2 = $request->input_number2;
                    $model->input_number3 = $request->input_number3;
                    $model->total_number = $request->input_number1+$request->input_number2+$request->input_number3;
                    $model->pass_number1 = $request->pass_number1;
                    $model->pass_number2 = $request->pass_number2;
                    $model->pass_number3 = $request->pass_number3;
                    $model->combined_subject_id  = $request->combined_subject_id;
                    $model->serial = $request->serial;
                    $model->gpa_calculation = $request->gpa_calculation;
                    $model->subject_group = $subject_group;
                   $model->save();
                }else{

                    return response()->json([  
                        'message' => 'Subject already exists in the target session',
                    ], 422);

                }

    
          

            DB::commit();

            return response()->json([
                  'message' => 'Data added successfully',
              ], 200);

         } catch (\Exception $e) {
              DB::rollback();
           
              return response()->json([
                  'message' => 'Failed to Add ',
                  'error' => $e->getMessage(),
              ], 500);
        }
    }

 
}
