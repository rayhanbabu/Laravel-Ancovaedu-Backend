<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StudentAttendanceResource;

class AttendanceResource extends JsonResource
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
            'time'=> $this->time,
            'date'=> $this->date,

            'sessionyear_id' => $this->sessionyear_id,
            'sessionyear_name' => $this->sessionyear->sessionyear_name ?? null,

            'programyear_id' => $this->programyear_id,
            'programyear_name' => $this->programyear->programyear_name ?? null,

            'level_id' => $this->level_id,
            'level_name' => $this->level->level_name ?? null,

            'faculty_id' => $this->faculty_id,
            'faculty_name' => $this->faculty->faculty_name ?? null,

            'department_id' => $this->department_id,
            'department_name' => $this->department->department_name ?? null,

            'section_id' => $this->section_id,
            'section_name' => $this->section->section_name ?? null,

            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject->subject_name ?? null,

            'attendances' => StudentAttendanceResource::collection($this->attendances),

           
        ];
    }
}
