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

    public function generateMarksheetPDF()
    {
          $data = [
              'title' => 'Laravel 10 Marksheet PDF',
              'date'  => date('m/d/Y')
           ];
          $file = 'marksheet.pdf';
          $pdf = PDF::setPaper('a4', 'portrait')->loadView('pdf.marksheet', $data);
          //return $pdf->download($file);  landscape
          return  $pdf->stream($file,array('Attachment'=>false));
     }

    public function generateTabulationPDF(){
        $data = [
            'title' => 'Laravel 10 Tabulation PDF',
            'date'  => date('m/d/Y')
        ];
        $file = 'tabulation.pdf';
        $pdf = PDF::setPaper('a4', 'portrait')->loadView('pdf.tabulation', $data);
        return $pdf->stream($file,array('Attachment'=>false));
    }


    public function generateSummaryPDF(){
        $data = [
            'title' => 'Laravel 10 Summary PDF',
            'date'  => date('m/d/Y')
        ];
        $file = 'summary.pdf';
        $pdf = PDF::setPaper('a4', 'landscape')->loadView('pdf.summary', $data);
        return $pdf->stream($file,array('Attachment'=>false));
    }
}
