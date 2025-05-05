<?php

namespace App\Services\AttendanceService;

use App\Models\Attendance;
use App\Models\Classdate;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;


class AttendanceAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {
            $user_auth = user();
            $username = $request->school_username;

              $validator = validator($request->all(), [     
                 'time' => 'required',
                 'date' => 'required|date|date_format:Y-m-d',
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',    
                 'subject_id' => 'required|integer|exists:subjects,id',          
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

             $enroll = Enroll::where('school_username', $username)->where('sessionyear_id', $request->sessionyear_id)
                ->where('programyear_id', $request->programyear_id)->where('level_id', $request->level_id)
                ->where('faculty_id', $request->faculty_id)->where('department_id', $request->department_id)
                ->where('section_id', $request->section_id)->get();

          if ($enroll->isEmpty()) {
                  return response()->json([
                        'message' => 'No students found for the given criteria',
                    ], 400);
          }

        $classdate = Classdate::where('school_username', $username)->where('sessionyear_id', $request->sessionyear_id)
            ->where('programyear_id', $request->programyear_id)->where('level_id', $request->level_id)
            ->where('faculty_id', $request->faculty_id)->where('department_id', $request->department_id)
            ->where('section_id', $request->section_id)->where('subject_id', $request->subject_id)
            ->where('date', $request->date)->first();

            if ($classdate) {
                return response()->json([
                    'message' => 'Class date already exists',
                ], 400);
            }else{

                $classdate = new CLassdate();
                $classdate->school_username = $request->school_username;
                $classdate->sessionyear_id = $request->sessionyear_id;
                $classdate->programyear_id = $request->programyear_id;
                $classdate->level_id = $request->level_id;
                $classdate->faculty_id = $request->faculty_id;
                $classdate->department_id = $request->department_id;
                $classdate->section_id = $request->section_id;
                $classdate->subject_id = $request->subject_id;
                $classdate->date = $request->date;
                $classdate->time = $request->time;
                $classdate->created_by = $user_auth->id;
                $classdate->save();

               foreach ($enroll as $student) {
                    $attendance = new Attendance();
                    $attendance->school_username = $request->school_username;
                    $attendance->classdate_id = $classdate->id;
                    $attendance->student_id = $student->student_id;
                    $attendance->status = 0; // Default status
                    $attendance->created_by = $user_auth->id;
                    $attendance->save();
                }

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
