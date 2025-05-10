<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AttendanceListResource extends JsonResource
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
            'student_name' => $this->student->english_name ?? null,

            'subject_id' => $this->classdate->subject_id,
            'subject_name' => $this->classdate->subject->subject_name ?? null,
            
            'time'=> $this->classdate->time,
            'date'=> $this->classdate->date,
            'status' => $this->status,

            'sessionyear_id' => $this->enroll->sessionyear_id,
            'sessionyear_name' => $this->enroll->sessionyear->sessionyear_name ?? null,
           
            
            'programyear_id' => $this->enroll->programyear_id,
            'programyear_name' => $this->enroll->programyear->programyear_name ?? null,

            'level_id' => $this->enroll->level_id,
            'level_name' => $this->enroll->level->level_name ?? null,

            'faculty_id' => $this->enroll->faculty_id,
            'faculty_name' => $this->enroll->faculty->faculty_name ?? null,

            'department_id' => $this->enroll->department_id,
            'department_name' => $this->enroll->department->department_name ?? null,

            'section_id' => $this->enroll->section_id,
            'section_name' => $this->enroll->section->section_name ?? null,

        


          

           
        ];
    }
}
