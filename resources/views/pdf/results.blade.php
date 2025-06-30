<!DOCTYPE html>
<html>
 <head>
	 <script>
 function printContent(e1){
   var restorepage= document.body.innerHTML;
   var printcontent= document.getElementById(e1).innerHTML;
   document.body.innerHTML=printcontent;
   window.print();
   document.body.innerHTML=restorepage;
  }
  
</script>

         <style>
	   td,table
         {
		    border:1px solid black;
			border-collapse:collapse;
			
		 }
		td
						{
                           padding:1px;
						   text-align:center;
						   font-family:Cambria,"Hoefler text","Liberation Serf",Times,"Times New Roman",serif;	
                           font-size:17px;						   
						} 
		 
		 th{
			 padding:1px;
			 text-align:left;
			 font-size:18px;
			 font-family:Cambria,"Hoefler text","Liberation Serf",Times,"Times New Roman",serif;		
			
			}
			
			th span{
			  margin-left:8px;
			}
	    #school{
		   font-size:24px;
		  text-align:center;
	   }	
	    #up{
		   font-size:17px;
		   text-align:center;
	   }		
	   #exam{
		   font-size:20px;
		   text-align:center;
	   }	
     
	#footer{
		 font-family:Cambria,"Hoefler text","Liberation Serf",Times,"Times New Roman",serif;
		 font-size:14px;
		 text-align:center;
	}
	.area{
	
	    *height:950px;
		*background-color:gray;
		width:700px;
	}
	
	.btn {
  border: none;
  color: white;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
}

 .success {background-color: #4CAF50;} /* Green */
 .success:hover {background-color: #46a049;}
	  	   
	  </style>	   




	</head>
<body>

  <center>
	 <button class="btn success" onclick ="printContent('div1')">Print Marksheet</button><br><br>
    <div id="div1">

	@if($student) 

		<div class="area">
		<table align="center">		

              <tr height="50">
                    <th colspan="2" rowspan="3" > <img src="{{ asset('schoolsoft4/public/uploads/admin/'.$school->image) }}" style=" margin-left:15px; width:100px; height:100px; "/> </th> 
				    <th colspan="6" id="school">{{$school->school}}</th>
				    <th ></th>
					<th></th>
			   </tr>
			   
			   <tr height="30">
				     <th colspan="6" id="up"> {{$school->address}} </th>
					 <th colspan="2" rowspan="5"> <img src="{{asset('images/grade.jpg')}}" style=" margin-left:10px; width:120px; height:155px; "/> </th>
			   </tr>
			   
			    <tr height="35">
				        <th colspan="6" id="exam"> {{examNameDes($student->exam)}}  -{{$student->year}} </th>	
			     </tr>

			   <tr height="30">
			          <th colspan="2" > <span>Student Nane</span> </th>
				      <th colspan="6">: {{$student->name}} </th>	
					  
			   </tr>
			   
			   <tr height="30">
				      <th colspan="2" > <span>Roll</span></th>
				      <th>: {{$student->roll}} </th>
					  <th> </th>
				      <th> </th>
					  <th> </th>
				      <th> </th>
			   </tr>
			   
			   <tr height="30">
				    <th colspan="2" > <span>Class</span></th>
				    <th>: {{$student->class}}  </th>
					<th> </th>
				    <th> </th>
					<th> </th>
				    <th> </th>
			   </tr>
			   
			     <tr height="30">
				      <th colspan="2"> <span>Student ID</span></th>
				      <th>: {{$student->stu_id}} </th>
					  <th> </th>
					  <th colspan="3">Shift</th>
				      <th colspan="3">: {{shift($student->section,$school->eiin)}} </th>
				 </tr>
			   
			     <tr height="30">
				      <th colspan="2"> <span>Section</span></th>
				      <th>: {{$student->section}}  </th>
					  <th> </th>
					  <th colspan="3">Group</th>
				      <th colspan="3">: {{$student->babu}}  </th>
				 </tr>
				
				  <tr height="30">
				       <th colspan="2"><span> {{examName($student->exam)}}  GPA</span></th>
				       <th>: {{$student->gpa}}</th>
					   <th></th>
					   <th colspan="3"> {{examName($student->exam)}}   Grade</th>
                       <th colspan="3">: {{$student->g}} </th>
				   </tr>
			 		

                @if($student->rs==2)    
		            <tr height="30">
				        <th colspan="2"><span> Half Yearly GPA</span></th>
				        <th>: {{$student->fgp}} </th>
					    <th></th>
					    <th colspan="3"> Half Yearly Grade </th>
                        <th colspan="3">: {{$student->fg}}  </th>
				   </tr>
				
			       <tr height="30">
				        <th colspan="2"><span>CGPA</span></th>
				        <th>: {{$student->cgp}}</th>
					    <th></th>
					    <th colspan="3">CGrade</th>
                        <th colspan="3">: {{$student->cg}}</th>
				   </tr>
                @else


				@endif
			
				<tr height="30">
				       <th  colspan="2"><span> </span></th>
				       <th> </th>
					   <th> </th>
					   <th colspan="3"></th>
				       <th colspan="3"> </th>
				</tr>
				
			   <tr height="10">
			        <th width="45"> </th>
				    <th width="110"></th>
				    <th width="110"></th>
					<th width="55"></th>
				    <th width="55"></th>
					<th width="55"></th>
				    <th width="55"></th>
					<th width="55"></th>
				    <th width="70"></th>
					<th width="70"></th>
			   </tr>
			   	    
			   <tr height="22" >
			        <td>Serial</td>
				    <td colspan="2" >Name of Subjects</td>
					<td>Written</td>
				    <td>MCQ</td>
					<td>Practical</td>
				    <td>Total</td>
				    <td>Highest</td>
				    <td>GPA</td>
				    <td>Grade</td>    
			   </tr>
			   
			
			   
	    @if($student->class=='Nine' OR $student->class=='Ten')
            <tr height="22">          
               <td> i</td>  
               <td  colspan="2"> <?php echo substr($student->sub11n,0,30);?> </td>
			   <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub11c}} @else @endif  </td>
               <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub11m}} @else @endif  </td>
               <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub11m}} @else @endif  </td>
               <td> </td>
               <td> </td>
               <td> </td>
               <td> </td>
          </tr>
         @else		  
		 @if(!empty(subjectshow('sub11',$student->class,$student->babu,$student->eiin)) && $student->sub11h)
           <tr height="22">          
                <td >i</td>  
                <td  colspan="2"> <?php echo substr($student->sub11n,0,30);?> </td>
                <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub11c}} @else @endif  </td>
                <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub11m}} @else @endif  </td>
                <td> @if(subjectshow('sub11',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub11m}} @else @endif  </td>
                <td> {{$student->sub11t}} </td>
                <td> {{$student->sub11h}} </td>
                <td> {{$student->sub11gp}}</td>
                <td> {{$student->sub11g}} </td>
           </tr>
        @else
            <tr height="22">
                 <td >i</td> <td  colspan="2" ></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
       @endif
	 @endif  
		
	   

	
	    @if(!empty(subjectshow('sub12',$student->class,$student->babu,$student->eiin)) && $student->sub12h)
           <tr height="22">          
               <td > ii </td>  
               <td  colspan="2"> <?php echo substr($student->sub12n,0,30); ?> </td>
               <td> @if(subjectshow('sub12',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub12c}} @else @endif  </td>
               <td> @if(subjectshow('sub12',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub12m}} @else @endif  </td>
               <td> @if(subjectshow('sub12',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub12m}} @else @endif  </td>
               <td> {{$student->sub12t}} </td>
               <td> {{$student->sub12h}} </td>
               <td> {{$student->sub12gp}} </td>
               <td> {{$student->sub12g}} </td>
           </tr>
        @else
           <tr height="22">
                  <td >ii</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
       @endif
	 

	   @if($student->class=='Nine' OR $student->class=='Ten')
            <tr>           
               <td >iii</td>  
               <td  colspan="2"> <?php echo substr($student->sub13n,0,30);?> </td>
			   <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub13c}} @else @endif  </td>
               <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub13m}} @else @endif  </td>
               <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub1m}} @else @endif  </td>
               <td> </td>
               <td> </td>
               <td> </td>
               <td> </td>
          </tr>
      @else
	   @if(!empty(subjectshow('sub13',$student->class,$student->babu,$student->eiin)) && $student->sub13h)
           <tr height="22">          
              <td >iii</td>  
              <td  colspan="2"> <?php echo substr($student->sub13n,0,30); ?> </td>
              <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub13c}} @else @endif  </td>
              <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub13m}} @else @endif  </td>
              <td> @if(subjectshow('sub13',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub13m}} @else @endif  </td>
              <td> {{$student->sub13t}}</td>
              <td> {{$student->sub13h}}</td>
              <td> {{$student->sub13gp}}</td>
              <td> {{$student->sub13g}}</td>
          </tr>
       @else
           <tr height="22">
                <td>iii</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif
   @endif  

	  
	  @if(!empty(subjectshow('sub14',$student->class,$student->babu,$student->eiin)) && $student->sub14h)
           <tr height="22">          
              <td > iv </td>  
              <td   colspan="2"> <?php echo substr($student->sub14n,0,30); ?> </td>
              <td> @if(subjectshow('sub14',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub14c}} @else @endif  </td>
              <td> @if(subjectshow('sub14',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub14m}} @else @endif  </td>
              <td> @if(subjectshow('sub14',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub14m}} @else @endif  </td>
              <td> {{$student->sub14t}}</td>
              <td> {{$student->sub14h}}</td>
              <td> {{$student->sub14gp}}</td>
              <td> {{$student->sub14g}}</td>
          </tr>
      @else
           <tr height="22">
                <td >iv</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif


	  @if(!empty(subjectshow('sub15',$student->class,$student->babu,$student->eiin)) && $student->sub15h)
           <tr height="22">          
              <td > v </td>  
              <td   colspan="2"> <?php echo substr($student->sub15n,0,30); ?> </td>
              <td> @if(subjectshow('sub15',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub15c}} @else @endif  </td>
              <td> @if(subjectshow('sub15',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub15m}} @else @endif  </td>
              <td> @if(subjectshow('sub15',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub15m}} @else @endif  </td>
              <td> {{$student->sub15t}}</td>
              <td> {{$student->sub15h}}</td>
              <td> {{$student->sub15gp}}</td>
              <td> {{$student->sub15g}}</td>
          </tr>
      @else
           <tr height="22">
                 <td>v</td> <td colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif		

			   
	  @if(!empty(subjectshow('sub16',$student->class,$student->babu,$student->eiin)) && $student->sub16h)
           <tr height="22">          
              <td> vi </td>  
              <td  colspan="2"> <?php echo substr($student->sub16n,0,30); ?> </td>
              <td> @if(subjectshow('sub16',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub16c}} @else @endif  </td>
              <td> @if(subjectshow('sub16',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub16m}} @else @endif  </td>
              <td> @if(subjectshow('sub16',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub16m}} @else @endif  </td>
              <td> {{$student->sub16t}}</td>
              <td> {{$student->sub16h}}</td>
              <td> {{$student->sub16gp}}</td>
              <td> {{$student->sub16g}}</td>
          </tr>
      @else
           <tr height="22">
                <td >vi</td> <td colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif


	@if(!empty(subjectshow('sub17',$student->class,$student->babu,$student->eiin)) && $student->sub17h)
           <tr height="22">          
              <td > vii </td>  
              <td  colspan="2"> <?php echo substr($student->sub17n,0,30); ?> </td>
              <td> @if(subjectshow('sub17',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub17c}} @else @endif  </td>
              <td> @if(subjectshow('sub17',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub17m}} @else @endif  </td>
              <td> @if(subjectshow('sub17',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub17m}} @else @endif  </td>
              <td> {{$student->sub17t}}</td>
              <td> {{$student->sub17h}}</td>
              <td> {{$student->sub17gp}}</td>
              <td> {{$student->sub17g}}</td>
          </tr>
      @else
           <tr height="22">
                <td >vii</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif


	  @if(!empty(subjectshow('sub18',$student->class,$student->babu,$student->eiin)) && $student->sub18h)
           <tr height="22">          
              <td> viii </td>  
              <td  colspan="2"> <?php echo substr($student->sub18n,0,30); ?> </td>
              <td> @if(subjectshow('sub18',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub18c}} @else @endif  </td>
              <td> @if(subjectshow('sub18',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub18m}} @else @endif  </td>
              <td> @if(subjectshow('sub18',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub18m}} @else @endif  </td>
              <td> {{$student->sub18t}}</td>
              <td> {{$student->sub18h}}</td>
              <td> {{$student->sub18gp}}</td>
              <td> {{$student->sub18g}}</td>
          </tr>
      @else
           <tr height="22">
                <td>viii</td> <td colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif

			 
	  @if(!empty(subjectshow('sub19',$student->class,$student->babu,$student->eiin)) && $student->sub19h)
           <tr>          
              <td  >ix </td>  
              <td  colspan="2"> <?php echo substr($student->sub19n,0,30); ?> </td>
              <td> @if(subjectshow('sub19',$student->class,$student->babu,$student->eiin)['cstatus']=='number') {{$student->sub19c}} @else @endif  </td>
              <td> @if(subjectshow('sub19',$student->class,$student->babu,$student->eiin)['mstatus']=='number') {{$student->sub19m}} @else @endif  </td>
              <td> @if(subjectshow('sub19',$student->class,$student->babu,$student->eiin)['pstatus']=='number') {{$student->sub19m}} @else @endif  </td>
              <td> {{$student->sub19t}}</td>
              <td> {{$student->sub19h}}</td>
              <td> {{$student->sub19gp}}</td>
              <td> {{$student->sub19g}}</td>
          </tr>
      @else
           <tr>
                <td>ix</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif


	  @if(!empty(subjectshow('sub20',$student->class,$student->babu,$student->eiin)) && $student->sub20h)
           <tr>          
              <td  >x</td>  
              <td   colspan="2"> <?php echo substr($student->sub20n,0,30); ?></td>
              <td> @if(subjectshow('sub20',$student->class,$student->babu,$student->eiin)['cstatus']=='number'){{$student->sub20c}} @else @endif  </td>
              <td> @if(subjectshow('sub20',$student->class,$student->babu,$student->eiin)['mstatus']=='number'){{$student->sub20m}} @else @endif  </td>
              <td> @if(subjectshow('sub20',$student->class,$student->babu,$student->eiin)['pstatus']=='number'){{$student->sub20m}} @else @endif  </td>
              <td> {{$student->sub20t}}</td>
              <td> {{$student->sub20h}}</td>
              <td> {{$student->sub20gp}}</td>
              <td> {{$student->sub20g}}</td>
          </tr>
      @else
           <tr>
                <td >x</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif
			  

	  @if(!empty(subjectshow('sub21',$student->class,$student->babu,$student->eiin)) && $student->sub21h)
           <tr>          
              <td  >xi</td>  
              <td  colspan="2"> <?php echo substr($student->sub21n,0,30); ?></td>
              <td> @if(subjectshow('sub21',$student->class,$student->babu,$student->eiin)['cstatus']=='number'){{$student->sub21c}} @else @endif  </td>
              <td> @if(subjectshow('sub21',$student->class,$student->babu,$student->eiin)['mstatus']=='number'){{$student->sub21m}} @else @endif  </td>
              <td> @if(subjectshow('sub21',$student->class,$student->babu,$student->eiin)['pstatus']=='number'){{$student->sub21m}} @else @endif  </td>
              <td> {{$student->sub21t}} </td>
              <td> {{$student->sub21h}} </td>
              <td> {{$student->sub21gp}} </td>
              <td> {{$student->sub21g}} </td>
          </tr>
      @else
           <tr>
                <td >xi</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif


	 @if(!empty(subjectshow('sub22',$student->class,$student->babu,$student->eiin)) && $student->sub22h)
           <tr>          
              <td >xii</td>  
              <td   colspan="2"> <?php echo substr($student->sub22n,0,30); ?></td>
              <td> @if(subjectshow('sub22',$student->class,$student->babu,$student->eiin)['cstatus']=='number'){{$student->sub22c}} @else @endif  </td>
              <td> @if(subjectshow('sub22',$student->class,$student->babu,$student->eiin)['mstatus']=='number'){{$student->sub22m}} @else @endif  </td>
              <td> @if(subjectshow('sub22',$student->class,$student->babu,$student->eiin)['pstatus']=='number'){{$student->sub22m}} @else @endif  </td>
              <td> {{$student->sub22t}}  </td>
              <td> {{$student->sub22h}}  </td>
              <td> {{$student->sub22gp}} </td>
              <td> {{$student->sub22g}}  </td>
          </tr>
      @else
           <tr>
                <td >xii</td> <td colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif

			  
	  @if(!empty(subjectshow('sub23',$student->class,$student->babu,$student->eiin)) && $student->sub23h)
           <tr>          
              <td >xiii</td>  
              <td  colspan="2"> <?php echo substr($student->sub22n,0,30); ?></td>
              <td> @if(subjectshow('sub23',$student->class,$student->babu,$student->eiin)['cstatus']=='number'){{$student->sub23c}} @else @endif  </td>
              <td> @if(subjectshow('sub23',$student->class,$student->babu,$student->eiin)['mstatus']=='number'){{$student->sub23m}} @else @endif  </td>
              <td> @if(subjectshow('sub23',$student->class,$student->babu,$student->eiin)['pstatus']=='number'){{$student->sub23m}} @else @endif  </td>
              <td> {{$student->sub23t}}  </td>
              <td> {{$student->sub23h}}  </td>
              <td> {{$student->sub23gp}} </td>
              <td> {{$student->sub23g}}  </td>
          </tr>
      @else
           <tr>
                <td >xiii</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif



	  @if(!empty(subjectshow('sub24',$student->class,$student->babu,$student->eiin)) && $student->sub24h)
           <tr>          
              <td >ivx</td>  
              <td  colspan="2"> <?php echo substr($student->sub22n,0,30); ?></td>
              <td> @if(subjectshow('sub24',$student->class,$student->babu,$student->eiin)['cstatus']=='number'){{$student->sub24c}} @else @endif  </td>
              <td> @if(subjectshow('sub24',$student->class,$student->babu,$student->eiin)['mstatus']=='number'){{$student->sub24m}} @else @endif  </td>
              <td> @if(subjectshow('sub24',$student->class,$student->babu,$student->eiin)['pstatus']=='number'){{$student->sub24m}} @else @endif  </td>
              <td> {{$student->sub24t}}  </td>
              <td> {{$student->sub24h}}  </td>
              <td> {{$student->sub24gp}} </td>
              <td> {{$student->sub24g}}  </td>
          </tr>
      @else
           <tr>
                <td >ivx</td> <td  colspan="2"></td><td></td><td></td><td></td><td></td><td></td> <td></td><td></td> 
           </tr>
      @endif

			   
			    <tr height="22" >
				    <td colspan="3">Total Marks  </td>
				    <td colspan="7"> {{$student->total}} </td>
			   </tr>
			
          </table> 
	     </div>
         
		
	
      
		 @else
	        <h2>No record present in the database! </h2>
		    <h2>Please Enter Valid Student ID </h2>
		 @endif
			  	  
      

       


		
	  
	 
			 
		   </div>
		   <br>
	  
	</center>
			
			

</body>
</html>

