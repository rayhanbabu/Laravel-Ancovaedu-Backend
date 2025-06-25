<?php

namespace App\Exports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Subject::select(
            'subject_name',
            'subject_code',
            'serial',
            'gpa_calculation',
            'input_lavel1',
            'input_lavel2',
            'input_lavel3',
            'input_number1',
            'input_number2',
            'input_number3',
            'total_number',
            'pass_number1',
            'pass_number2',
            'pass_number3',
            'subject_category',
            'religion_id',
            'subject_type',
        )
        ->where($this->filters)
        ->get();
    }

    public function headings(): array
    {
        return [
            'Subject Name',
            'Subject Code',
            'Serial',
            'GPA Calculation',
            'Input Level 1',
            'Input Level 2',
            'Input Level 3',
            'Input Number 1',
            'Input Number 2',
            'Input Number 3',
            'Total Number',
            'Pass Number 1',
            'Pass Number 2',
            'Pass Number 3',
            'Subject Category',
            'Religion ID',
            'Subject Type',
        ];
    }
}
