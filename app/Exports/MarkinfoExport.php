<?php

namespace App\Exports;

use App\Models\Markinfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarkinfoExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
         return Markinfo::select([
           'start',
           'end',
           'gpa',
           'grade',
           'gparange',
       ])
    ->where($this->filters)
    ->get();
    }

   public function headings(): array
  {
    return [
        'Start',
        'End',
        'GPA',
        'Grade',
        'GPA Range',
    ];
  }
}
