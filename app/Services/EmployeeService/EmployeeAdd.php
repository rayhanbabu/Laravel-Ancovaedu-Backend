<?php

namespace App\Services\EmployeeService;

use App\Models\Employee;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use Illuminate\Validation\Rule;

class EmployeeAdd
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
         try {

            $user_auth = user();
            $noSpace = str_replace(' ', '', $request->english_name);
            $username = substr($noSpace, 0, 10).$request->phone;

              $validator = validator($request->all(), [
                 'english_name' => 'required',
                 'bangla_name' => 'required',
                 'phone' => 'required|unique:users,phone',
                 'email' => 'email|unique:users,email',
                 'password' => 'required|regex:/^[a-zA-Z\d]*$/|min:6',
                 'profile_picture' => 'image|mimes:jpeg,png,jpg|max:600',
                 'religion_id' => 'required|integer|exists:religions,id',                
                 'gender' => 'required',
                 'level_id' => 'exists:levels,id',
                 'faculty_id' => 'exists:faculties,id',
                 'department_id' => 'exists:departments,id',
                 'designation_id' => 'required|integer|exists:designations,id',
                     
            ]);

            if($validator->fails()) {
                return response()->json([
                     'message' => 'Validation failed',
                     'errors' => $validator->errors(),
                 ], 422);
             }

           
         

            $user = new User();
            $user->name = $request->english_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->username = $username;

            if ($request->hasfile('profile_picture')) {
                $user->profile_picture = $this->uploadFile($request->file('profile_picture'), 'profile_picture');
            }

            $user->save();

            User_role::create([
                'user_id' => $user->id,
                'role_type' => 'Employee',
                'created_by' => $user_auth->id,
            ]);

            $Employee = new Employee();
            $Employee->user_id = $user->id;
            $Employee->school_username = $request->school_username;
            $Employee->bangla_name = $request->bangla_name;
            $Employee->english_name = $request->english_name;
            $Employee->gender= $request->gender;
            $Employee->religion_id = $request->religion_id;
            $Employee->relationship = $request->relationship;
            $Employee->blood_group = $request->blood_group;
            $Employee->joining_date = $request->joining_date;
            $Employee->index_number = $request->index_number;
            $Employee->tin_number = $request->tin_number;
            $Employee->dob = $request->dob;
            $Employee->father_name = $request->father_name;
            $Employee->mother_name = $request->mother_name;
            $Employee->spouse_name = $request->spouse_name;
            $Employee->present_address = $request->present_address;
            $Employee->permanent_address = $request->permanent_address;
            $Employee->level_id = $request->level_id;
            $Employee->faculty_id = $request->faculty_id;
            $Employee->department_id = $request->department_id;
            $Employee->designation_id = $request->designation_id;

            $Employee->account_name = $request->account_name;
            $Employee->account_number = $request->account_number;
            $Employee->bank_name = $request->bank_name;
            $Employee->branch_name = $request->branch_name;
            $Employee->swift_code = $request->swift_code;
            $Employee->routing_number = $request->routing_number;


            $Employee->created_by = $user_auth->id; 
            $Employee->save();

           


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
