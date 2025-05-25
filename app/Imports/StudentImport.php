<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Role;
use App\Models\User;
use App\Models\User_role;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use App\Models\Enroll;


 class  StudentImport implements ToModel, WithBatchInserts, WithChunkReading, ShouldQueue
    {

    public function __construct($school_username, $sessionyear_id, $programyear_id, $level_id, $faculty_id, 
    $department_id, $section_id, $user_auth,$enroll_group)
       {
           $this->school_username = $school_username;
           $this->sessionyear_id = $sessionyear_id;
           $this->programyear_id = $programyear_id;
           $this->level_id = $level_id;
           $this->faculty_id = $faculty_id;
           $this->department_id = $department_id;
           $this->section_id = $section_id;
           $this->user_id = $user_auth->id;  
           $this->enroll_group = $enroll_group; 
       }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function batchSize(): int
    {
        return 1000; // Set your desired batch size here
    }

    public function chunkSize(): int
    {
        return 1000; // Set your desired chunk size here
    }



    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Create User
            $user = new User();
            $user->name = $row[0];
            $user->phone = $row[2];
            $user->email = $row[3];
            $user->password = bcrypt("Rayhan123");
            $user->status = 1; 
            $user->first_phone = substr($row[2], 0, 3);
            $user->last_phone = substr($row[2], 3);

            // Generate a username based on phone or name
            $user->username = $this->school_username . $row[2];
            $user->save();
    
            // Assign role
            User_role::create([
                'user_id'   => $user->id,
                'role_type' => 'Student',
                'created_by'=> $this->user_id,
            ]);
    
            // Create Student record
            $student = new Student();
            $student->user_id         = $user->id;
            $student->school_username = $this->school_username;
            $student->english_name    = $row[0];
            $student->bangla_name     = $row[1];
            $student->gender          = $row[4];
            $student->religion_id     = $row[5];
            $student->created_by      = $this->user_id;
            $student->save();
    
            // Create Enroll record
            $enroll = new Enroll();
            $enroll->student_id       = $student->id;
            $enroll->user_id          = $user->id;
            $enroll->roll             = $row[6];
            $enroll->school_username  = $this->school_username;
            $enroll->sessionyear_id   = $this->sessionyear_id;
            $enroll->programyear_id   = $this->programyear_id;
            $enroll->level_id         = $this->level_id;
            $enroll->faculty_id       = $this->faculty_id;
            $enroll->department_id    = $this->department_id;
            $enroll->section_id       = $this->section_id;
            $enroll->enroll_group     = $this->enroll_group;
            $enroll->created_by       = $this->user_id;
            $enroll->created_type     = "Student";
            $enroll->save();
    
           
        });
    }


}