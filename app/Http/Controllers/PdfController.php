<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generatePDF()
    {
        $data = [
            'title' => 'Laravel 10 DOMPDF Example',
            'date'  => date('m/d/Y')
        ];
        $pdf = Pdf::loadView('pdf.myPDF', $data);
        return $pdf->download('sample.pdf');
    }
}
