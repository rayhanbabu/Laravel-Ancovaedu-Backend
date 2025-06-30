@include('pdf/fpdf182/fpdf')
<?php

class PDF extends FPDF
{
protected $col = 0; // Current column
protected $y0;      // Ordinate of column start

function Header()
{
    global $title;
    $this->SetFont('Arial','B',15);
    $w = $this->GetStringWidth($title)+6;
    $this->SetX((210-$w)/2);
 
    //$this->SetLineWidth(1);
    $this->Cell($w,5,'',0,1,'C');
    //$this->Ln(10);
    // Save ordinate
    $this->y0 = $this->GetY();
}


function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function SetCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 10+$col*97.5;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    if($this->col<1)
    {
        $this->SetCol($this->col+1);
        $this->SetY($this->y0);
        return false;
    }
    else
    {
        $this->SetCol(0);
        return true;
    }
}




function ChapterBody($student,$examinfo,$color)
{
    // Read text file
   
    // Font
    $this->SetFont('Times','',12);
    // Output text in a 6 cm width column
    //$this->MultiCell(60,5,'');
    $this->SetDrawColor($color->color3,$color->color4,$color->color22);
    $this->SetTextColor($color->color3,$color->color4,$color->color22);

    foreach($student as $row){
$this->SetFont('Times','B',12);
     $this->Cell(83,12,'SeatPlan '.examNameDes($examinfo->exam).'-'.$examinfo->year,'TLR',0 , 'C' );
     $this->Cell(14.5,12,'',0,1 , 'L' );
 $this->SetFont('Times','',12);
    $this->Cell(15,6,'Stu Id','L',0 , 'L' );
	$this->Cell(68,6,': '.$row['stu_id'],'R',0 , 'L' );
	$this->Cell(14.5,6,'',0,1 , 'L' );

    $this->Cell(15,6,'Roll','L',0 , 'L' );
	$this->Cell(24,6,': '.$row['roll'],0,0 , 'L' );
    $this->Cell(15,6,'Section',0,0 , 'L' );
	$this->Cell(29,6,': '.$row['section'],'R',0 , 'L' );
	$this->Cell(14.5,6,'',0,1 , 'L' );

    $this->Cell(15,6,'Name','L',0 , 'L' );
	$this->Cell(68,6,substr(strtoupper(": ".$row['name']),0,24),'R',0 , 'L' );
	$this->Cell(14.5,6,'',0,1 , 'L' );
   
     $this->Cell(15,6,'Class','BL',0 , 'L' );
$this->SetFont('Times','B',12);
	  $this->Cell(24,6,': '.$row['class'],'B',0 , 'L' );
 $this->SetFont('Times','',12);    
    $this->Cell(15,6,'Group','B',0 , 'L' );
$this->SetFont('Times','B',12);
	$this->Cell(29,6,': '.$row['babu'],'RB',0 , 'L' );

	$this->Cell(14.5,6,'',0,1 , 'L' );

   
    $this->Ln(15);


	
	
   }
	
	
	
	
	
	
  
   
    
}

function PrintChapter($student,$examinfo,$color)
{
    $this->AddPage();
    $this->ChapterBody($student,$examinfo,$color);
}
}

$pdf = new PDF();


$pdf->SetFont('Courier','',14);
$title = 'SetPlan';
$pdf->SetTitle($title);
$pdf->SetAuthor('Jules Verne');
$pdf->PrintChapter($student,$examinfo,$color);


$pdf->Output($file,'I');
exit;



?>