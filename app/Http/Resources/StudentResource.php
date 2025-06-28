<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'roll' => $this->roll,
            'school_username' => $this->school_username,
            'enroll_group' => $this->enroll_group,
            'english_name' => $this->student->english_name,
            'bangla_name' => $this->student->bangla_name,
            'profile_picture' => $this->user->profile_picture,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'status' => $this->user->status,
            'user_id' => $this->user_id,
          
            'religion_id' => $this->student->religion_id,
            'religion_name' => $this->religion->religion_name,

            'sessionyear_id' => $this->sessionyear_id,
            'sessionyear_name' => $this->sessionyear->sessionyear_name,
            'programyear_id' => $this->programyear_id,
            'programyear_name' => $this->programyear->programyear_name,
            'level_id' => $this->level_id,
            'level_name' => $this->level->level_name,
            'level_category' => $this->level_category,
            'faculty_id' => $this->faculty_id,
            'faculty_name' => $this->faculty->faculty_name,
            'department_id' => $this->department_id,
            'department_name' => $this->department->department_name,
            'section_id' => $this->section_id,
            'section_name' => $this->section->section_name,

            'main_subject1_id' => $this->main_subject1,
            'main_subject2_id' => $this->main_subject2,
            'main_subject3_id' => $this->main_subject3,
            'additional_subject_id' => $this->additional_subject,



            'father_name' => $this->student->father_name,
            'father_phone' => $this->student->father_phone,
            'mother_name' => $this->student->mother_name,
            'dob' => $this->student->dob,
            'registration' => $this->student->registration,
            'gender' => $this->student->gender,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
