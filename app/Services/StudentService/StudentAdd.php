<?php

namespace App\Services\StudentService;

use App\Models\Student;
use App\Models\User;
use App\Models\School;
use App\Models\User_role;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class StudentAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $username = $request->school_username . $request->phone;

              $validator = validator($request->all(), [
                 'english_name' => 'required',
                 'bangla_name' => 'required',
                 'phone' => 'required|unique:users,phone',
                 'password' => 'required|regex:/^[a-zA-Z\d]*$/|min:6',
                 'profile_picture' => 'image|mimes:jpeg,png,jpg|max:600',
                 'religion_id' => 'required|integer|exists:religions,id',
                 'gender' => 'required',
                 'roll' => 'required|integer',
                 'sessionyear_id' => 'required|integer|exists:sessionyears,id',
                 'programyear_id' => 'required|integer|exists:programyears,id',
                 'level_id' => 'required|integer|exists:levels,id',
                 'faculty_id' => 'required|integer|exists:faculties,id',
                 'department_id' => 'required|integer|exists:departments,id',
                 'section_id' => 'required|integer|exists:sections,id',              
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

             if($request->email){
                $validator = validator($request->all(), [
                   'email' => 'required|email|unique:users,email',
                ]);
                if($validator->fails()) {
                    return response()->json([
                         'message' => 'Validation failed',
                         'errors' => $validator->errors(),
                     ], 422);
                  }

               $email = $request->email;    
        }else{
               $email = $request->phone."@gmail.com";
        }

         
            

            $user = new User();
            $user->name = $request->english_name;
            $user->email = $email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->username = $username;

            if ($request->hasfile('profile_picture')) {
                $user->profile_picture = $this->uploadFile($request->file('profile_picture'), 'profile_picture');
            }

            $user->save();

            User_role::create([
                'user_id' => $user->id,
                'role_type' => 'Student',
                'created_by' => $user_auth->id,
            ]);

            $student = new Student();
            $student->user_id = $user->id;
            $student->school_username = $request->school_username;
            $student->bangla_name = $request->bangla_name;
            $student->english_name = $request->english_name;
            $student->gender= $request->gender;
            $student->religion_id = $request->religion_id;
            $student->father_name = $request->father_name;
            $student->father_phone = $request->father_phone;
            $student->mother_name = $request->mother_name;
            $student->registration = $request->registration;
            $student->dob= $request->dob;
            $student->created_by = $user_auth->id; 
            $student->save();

            $enroll = new Enroll();
            $enroll->student_id = $student->id;
            $enroll->user_id = $user->id;
            $enroll->school_username = $request->school_username;
            $enroll->sessionyear_id = $request->sessionyear_id;
            $enroll->programyear_id = $request->programyear_id;
            $enroll->level_id = $request->level_id;
            $enroll->faculty_id = $request->faculty_id;
            $enroll->department_id = $request->department_id;
            $enroll->section_id = $request->section_id;
            $enroll->roll = $request->roll;
            $enroll->created_by = $user_auth->id;
            $enroll->created_type = "Student";
            $enroll->save();


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

    private function uploadFile($file, $prefix)
    {
        $fileName = $prefix . rand() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/admin'), $fileName);
        return $fileName;
    }
 

  }
