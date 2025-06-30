@include('pdf/fpdf182/fpdf')
   <?php

    $pdf = new FPDF();
    $pdf->AddPage();		
    $pdf->SetDrawColor($data['color']->color1,$data['color']->color2,$data['color']->color21);
    $pdf->SetTextColor($data['color']->color1,$data['color']->color2,$data['color']->color21);	  
       $row=$data['student'];
    for ($i = 0; $i < $row->count(); $i++){
     // 2nd part 

      $pdf->Cell(190,5,'',0,1);
      $pdf->SetFont('Times','',20);
      $pdf->Image(public_path('uploads/admin/'.$data['school']->image),15,17,-200);    //School Logu
      $pdf->Image(public_path('uploads/admin/'.$data['school']->image),15,150,-200);   //School Logu

        // if($i % 2 != 0){
        //     if(empty($row[$i]->image)){
        //        }else{
        //     $pdf->Image('uploads/student/'.$row[$i]->image,165,150,-310);
        //       }
        //  }else{
        //       if(empty($row[$i]->image)){
        //        }else{
        //         $pdf->Image('uploads/student/'.$row[$i]->image,165,17,-310);
        //        } 
        //  }
      $pdf->Cell(190,4,'','LRT',1,'R');
      $pdf->SetFont('Times','',$data['school']->ansize);	
      $pdf->Cell(190,10,$data['school']->school,'LR',1 , 'C' );
      $pdf->SetFont('Times','',$data['school']->sasize);	 
      $pdf->Cell(190,7,$data['school']->address,'LR',1 , 'C' );
      $pdf->SetFont('Times','B',15);	    
      $pdf->Cell(190,7,'Admit Card','LR',1 ,'C' );  
      $pdf->SetFont('Times','',13);



      $pdf->Cell(5,5,'','L',0 , 'L' );  
      $pdf->Cell(25,5,'Name ',0,0 , 'L' );
      $pdf->Cell(95,5,': '.$row[$i]->name,0,0 , 'L' );
      $pdf->Cell(15,5,'Exam ',0,0 , 'L' );
      $pdf->Cell(50,5,': '.examName($data['examinfo']->exam),'R',1, 'L' );	



       $pdf->Cell(5,5,'','L',0 , 'L' );  
       $pdf->Cell(25,5,'Roll ',0,0 , 'L' );
       $pdf->Cell(30,5,': '.$row[$i]->roll,0,0 , 'L' );
       $pdf->Cell(20,5,'Stu ID',0,0 , 'L' );
       $pdf->Cell(45,5,': '.$row[$i]->stu_id,0,0 , 'L' );
       $pdf->Cell(15,5,'Class',0,0 , 'L' );
       $pdf->Cell(50,5,': '.$row[$i]->class,'R',1, 'L' );

       $pdf->Cell(5,5,'','L',0 , 'L' );  
       $pdf->Cell(25,5,'Section ',0,0 , 'L' );
       $pdf->Cell(30,5,': '.$data['section'],0,0 , 'L' );
       $pdf->Cell(20,5,'Shift',0,0 , 'L' );
       $pdf->Cell(45,5,': '.shift($data['section'],$data['school']->eiin),0,0,'L' );
       $pdf->Cell(15,5,'Group',0,0 , 'L' );
       $pdf->Cell(50,5,': '.$data['babu'],'R',1, 'L' );

       $pdf->SetFont('Times','B',14);	
       $pdf->Cell(190,7,'Exam Routine','LR',1, 'C');

        $pdf->SetFont('Times','',11);	
        $pdf->Cell(47,5,'Date & Time',1,0 , 'C' );
        $pdf->Cell(48,5,'Subjects',1,0 , 'C' );
        $pdf->Cell(47,5,'Date & Time',1,0 , 'C' );
        $pdf->Cell(48,5,'Subjects',1,1, 'C' );

      

        if($data['sub1s']!=''){
            $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date1)).','.$data['admit']->time1,1,0 , 'L' );
            $pdf->Cell(48,5,$data['sub1s'],1,0 , 'L' );	}else{ 
            $pdf->Cell(47,5,'','L',0 , 'L' ); 
            $pdf->Cell(48,5,'','',0 , 'L' );}
        if($data['sub2s']!=''){
            $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date2)).','.$data['admit']->time2,1,0 , 'L' );
            $pdf->Cell(48,5,$data['sub2s'],1,1 , 'L' );   
                }else{ 
            $pdf->Cell(47,5,'',0,0 , 'L' );
            $pdf->Cell(48,5,'','R',1 , 'L' );    }


            if($data['sub3s']!=''){
               $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date3)).','.$data['admit']->time3,1,0 , 'L' );
               $pdf->Cell(48,5,$data['sub3s'],1,0 , 'L' );	}else{ 
               $pdf->Cell(47,5,'','L',0 , 'L' ); 
               $pdf->Cell(48,5,'','',0 , 'L' );}
           if($data['sub4s']!=''){
               $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date4)).','.$data['admit']->time4,1,0 , 'L' );
               $pdf->Cell(48,5,$data['sub4s'],1,1 , 'L' );   
                   }else{ 
               $pdf->Cell(47,5,'',0,0 , 'L' );
               $pdf->Cell(48,5,'','R',1 , 'L' );    }
            


     if($data['sub5s']!=''){
        $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date5)).','.$data['admit']->time5,1,0 , 'L' );
        $pdf->Cell(48,5,$data['sub5s'],1,0 , 'L' );	}else{ 
        $pdf->Cell(47,5,'','L',0 , 'L' ); 
        $pdf->Cell(48,5,'','',0 , 'L' );}
   if($data['sub6s']!=''){
       $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date6)).','.$data['admit']->time6,1,0 , 'L' );
       $pdf->Cell(48,5,$data['sub6s'],1,1 , 'L' );   
        }else{ 
       $pdf->Cell(47,5,'',0,0 , 'L' );
        $pdf->Cell(48,5,'','R',1 , 'L' );    }  


        if($data['sub7s']!=''){
         $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date7)).','.$data['admit']->time7,1,0 , 'L' );
         $pdf->Cell(48,5,$data['sub7s'],1,0 , 'L' );	}else{ 
         $pdf->Cell(47,5,'','L',0 , 'L' ); 
         $pdf->Cell(48,5,'','',0 , 'L' );}
    if($data['sub8s']!=''){
        $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date8)).','.$data['admit']->time8,1,0 , 'L' );
        $pdf->Cell(48,5,$data['sub8s'],1,1 , 'L' );   
         }else{ 
        $pdf->Cell(47,5,'',0,0 , 'L' );
         $pdf->Cell(48,5,'','R',1 , 'L' );    }  


         
        if($data['sub9s']!=''){
         $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date9)).','.$data['admit']->time9,1,0 , 'L' );
         $pdf->Cell(48,5,$data['sub9s'],1,0 , 'L' );	}else{ 
         $pdf->Cell(47,5,'','L',0 , 'L' ); 
         $pdf->Cell(48,5,'','',0 , 'L' );}
    if($data['sub10s']!=''){
        $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date10)).','.$data['admit']->time10,1,0 , 'L' );
        $pdf->Cell(48,5,$data['sub10s'],1,1 , 'L' );   
         }else{ 
        $pdf->Cell(47,5,'',0,0 , 'L' );
         $pdf->Cell(48,5,'','R',1 , 'L' ); }  


         if($data['sub11s']!=''){
            $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date11)).','.$data['admit']->time11,1,0 , 'L' );
            $pdf->Cell(48,5,$data['sub11s'],1,0 , 'L' );	}else{ 
            $pdf->Cell(47,5,'','L',0 , 'L' ); 
            $pdf->Cell(48,5,'','',0 , 'L' );    }
       if($data['sub12s']!=''){
           $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date12)).','.$data['admit']->time12,1,0 , 'L' );
           $pdf->Cell(48,5,$data['sub12s'],1,1 , 'L' );   
            }else{ 
             $pdf->Cell(47,5,'',0,0 , 'L' );
            $pdf->Cell(48,5,'','R',1 , 'L' );    } 
                     

            if($data['sub13s']!=''){
               $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date13)).','.$data['admit']->time13,1,0 , 'L' );
               $pdf->Cell(48,5,$data['sub13s'],1,0 , 'L' );	}else{ 
               $pdf->Cell(47,5,'','L',0 , 'L' ); 
               $pdf->Cell(48,5,'','',0 , 'L' );}
          if($data['sub14s']!=''){
              $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date14)).','.$data['admit']->time14,1,0 , 'L' );
              $pdf->Cell(48,5,$data['sub14s'],1,1 , 'L' );   
               }else{ 
              $pdf->Cell(47,5,'',0,0 , 'L' );
               $pdf->Cell(48,5,'','R',1 , 'L' ); }  



          if($data['sub15s']!=''){
               $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date15)).','.$data['admit']->time15,1,0 , 'L' );
               $pdf->Cell(48,5,$data['sub15s'],1,0 , 'L' );	}else{ 
               $pdf->Cell(47,5,'','L',0 , 'L' ); 
               $pdf->Cell(48,5,'','',0 , 'L' );}
          if($data['sub16s']!=''){
              $pdf->Cell(47,5,date('d/m/y',strtotime($data['admit']->date16)).','.$data['admit']->time16,1,0 , 'L' );
              $pdf->Cell(48,5,$data['sub16s'],1,1 , 'L' );   
               }else{ 
              $pdf->Cell(47,5,'',0,0 , 'L' );
               $pdf->Cell(48,5,'','R',1 , 'L' );    }  



          
        $pdf->Cell(190,5,'','RL',1);                           
               
            
        //  $pdf->Image('images/rayhanbabu.jpg',150,110,-250); 
        //  $pdf->Image('images/rayhanbabu.jpg',150,245,-250);   
            
      
                                    

        $pdf->Cell(190,10,'','LR',1);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(170,5,'Head Teacher Signature','L',0,'R');
        $pdf->Cell(20,5,'','R',1,'R');


         $pdf->Cell(190,3,'','LRB',1,'R');

         $pdf->Cell(100,10,'---',0,0);
         $pdf->Cell(90,10,'---',0,1,'R');

       }
        $pdf->Output($data['file'],'I');
        exit;


?>