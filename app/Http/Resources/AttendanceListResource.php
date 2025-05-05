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

            'sessionyear_id' => $this->classdate->sessionyear_id,
            'sessionyear_name' => $this->classdate->sessionyear->sessionyear_name ?? null,
           
            
            'programyear_id' => $this->classdate->programyear_id,
            'programyear_name' => $this->classdate->programyear->programyear_name ?? null,

            'level_id' => $this->classdate->level_id,
            'level_name' => $this->classdate->level->level_name ?? null,

            'faculty_id' => $this->classdate->faculty_id,
            'faculty_name' => $this->classdate->faculty->faculty_name ?? null,

            'department_id' => $this->classdate->department_id,
            'department_name' => $this->classdate->department->department_name ?? null,

            'section_id' => $this->classdate->section_id,
            'section_name' => $this->classdate->section->section_name ?? null,

        


          

           
        ];
    }
}
