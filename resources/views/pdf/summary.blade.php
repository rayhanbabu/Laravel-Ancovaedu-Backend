<!DOCTYPE html>
<html>
<head>
<style>
	   td,table
         {
		    border:1px solid black;
		 	border-collapse:collapse;
          color:black;
		 }
       td
		{
           padding:1px;
		     text-align:center;
		     font-family:Cambria,"Hoefler text","Liberation Serf",Times,"Times New Roman",serif;	
           font-size:14px;
           height:16px;						   
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
		   font-size:22px;
		   text-align:center;
            padding:10px;
            height:40px;
	   }	
	    #up{
		   font-size:17px;
		   text-align:center;
           height:35px;
	   }		
	   #exam{
		   font-size:20px;
		   text-align:center;
	   }	

     #rightside{
      margin-left:10px;
      text-align:center;
     }
     
    #footer{
      height:75px;
      text-align:right;
      font-size:15px;

    }

    #headerhight{
       height:24px;
    }

    .square-square{
  height: 15px;
  width: 15px;
  background-color:black;
}

.circle-circle {
  height: 15px;
  width: 15px;
  background-color: black;
  border-radius: 50%;
}

.triangle-up {
	width: 0;
	height: 0;
	border-left: 10px solid transparent;
	border-right: 10px solid transparent;
	border-bottom: 20px solid black;
}


	  	   
	  </style>	   


</head>
<body>


<table>

<tr>
    <th id="school" colspan="33">  Birol Adarsha High School </th>
</tr>

<tr>
    <th id="up" colspan="33">  Address Line 1, Address Line 2, City, State, Zip Code </th>
</tr>

<tr>
   <th id="up" colspan="33">Summary Sheets - 2024</th>
</tr>


<tr>
   <th id="rightside" ></th>
   <th colspan="2">Exam</th>
   <th colspan="4">:  </th>
   <th colspan="2"></th>
   <th colspan="4"></th>
   <th colspan="2">Class</th>
   <th colspan="3">:  </th>
   <th colspan="3"></th> 
   <th colspan="2"></th>
   <th colspan="3"></th> 
   <th colspan="3"></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
</tr>


 <tr>
      <th id="headerhight" colspan="33"></th>
 </tr>

<tr>
   <th width="20"></th>
   <th width="20"></th>
   <th width="20"></th>
   <th width="25"></th>
   <th width="25"></th>
   <th width="25"></th>
   <th width="25"></th>
   <th width="20"></th>
   <th width="25"></th>
   <th width="25"></th>
   
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
   <th width=""></th>
  
   <th width="25" ></th>
   <th width="25" ></th>
   <th width="25" ></th>
   <th width="20" ></th>
   <th width="25" ></th>
   <th width="25" ></th>
   <th width="25" ></th>
   <th width="25" ></th>
   <th width="25" ></th>
</tr>



<tr>
  <th  style="text-align:center;" colspan="33">Overall Summary</th>
</tr>

<tr>
   <td colspan="6">Subjects</td>
   <td>Pass</td>
   <td>Fail</td>
   <td colspan="5">Subjects</td>
   <td>Pass</td>
   <td>Fail</td>
   <td colspan="5">Subjects</td>
   <td>Pass</td>
   <td>Fail</td>
   <td colspan="5">Subjects</td>
   <td>Pass</td>
   <td>Fail</td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
</tr> 

<tr>
    
    @if($class=='Ten')
         <td colspan="6"></td><td></td> <td></td>
    @else
           @if(empty(matchsubcode($student1->sub11code,$tags)))
       <td colspan="6"></td><td></td> <td></td>
     @else 
       <td colspan="6">{{$student1->sub11n}}</td>
       <td>{{$sum->sub11}}</td>
       <td>{{$sum->total_stu-$sum->sub11}}</td>
      @endif
   @endif
  

  
        @if(empty(matchsubcode($student1->sub12code,$tags)))
          <td colspan="5"></td> <td></td> <td></td>
        @else 
         @if($class=='Ten')
           <td colspan="5">{{substr($student1->sub12n,0,6)}}</td>
           <td>{{$sum->sub12}}</td>
           <td>{{$sum->total_stu-$sum->sub12}}</td>
         @else
           <td colspan="5">{{$student1->sub12n}}</td>
           <td>{{$sum->sub12}}</td>
           <td>{{$sum->total_stu-$sum->sub12}}</td>
      @endif
    @endif

  @if($class=='Ten')
       <td colspan="5"></td><td></td> <td></td>
  @else
   @if(empty(matchsubcode($student1->sub13code,$tags)))
      <td colspan="5"></td><td></td><td></td>  
   @else 
      <td colspan="5">{{$student1->sub13n}} </td>
      <td>{{$sum->sub13}}</td>
      <td>{{$sum->total_stu-$sum->sub13}}</td>
   @endif
 @endif  

    @if(empty(matchsubcode($student1->sub14code,$tags)))
        <td colspan="5"></td> <td></td> <td></td>
    @else 
       @if($class=='Ten')
           <td colspan="5">{{substr($student1->sub14n,0,7)}}</td>
           <td>{{$sum->sub14}}</td>
           <td>{{$sum->total_stu-$sum->sub14}}</td>
         @else
        <td colspan="5"> {{$student1->sub14n}} 
        <td>{{$sum->sub14}}</td>
        <td>{{$sum->total_stu-$sum->sub14}}</td>
    @endif 
    @endif
    
    <td></td>
   <td></td>
   <td></td>
   <td></td>
</tr> 

<tr>
  
       <td colspan="6"> bangla </td>
       <td> 34 </td>
       <td> 56 </td>
  


  
        <td colspan="5"> English </td>
        <td> 10 </td>
        <td> 56 </td>

 
      <td colspan="5"> math </td>
      <td>0</td>
      <td></td>


        <td colspan="5"> Science  </td>
        <td> 12 </td>
        <td> 45 </td>
  
    <td></td>
    <td></td>
    <td></td>
    <td></td>   
</tr> 


<tr>
  
       <td colspan="6"> dfgf </td>
       <td> 3</td>
       <td>3 </td>
 

  
        <td colspan="5">545</td>
        <td>2</td>
        <td>23</td>



      <td colspan="5"> fhfgh fgh</td>
      <td>23</td>
      <td>34</td>
 

       <td colspan="5"> dfgdf</td>
       <td>32</td>
       <td>23</td>


    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr> 


<tr>
 
       <td colspan="6"> erert </td>
       <td> 44 </td>
       <td> 34 </td>
   

    @if(empty(matchsubcode($student1->sub24code,$tags)))
        <td colspan="5"></td> <td></td> <td></td>
    @else 
        <td colspan="5">{{$student1->sub24n}}</td>
        <td>{{$sum->sub24}}</td>
        <td>{{$sum->total_stu-$sum->sub24}}</td>
    @endif
        <td colspan="5"></td><td></td><td></td>  
        <td colspan="5"></td> <td></td><td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
 </tr> 

<tr>
   <td colspan="6">Total Student</td>
   <td colspan="2">{{$sum->total_stu}}</td>
   <td colspan="5">Total Pass</td>
   <td colspan="2">{{$total_pass}}</td>
   <td colspan="5">Total Fail</td>
   <td colspan="2">{{$sum->total_stu-$total_pass}}</td>
   <td colspan="5"></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
</tr> 

 <tr>
     <th id="headerhight" colspan="33"></th>
 </tr>

  <tr>
     <th style="text-align:center;" colspan="33">Individual Summary</th>
 </tr>

<tr>
   <td colspan="2">Stu_Id</td>
   <td>Roll</td>
   <td>Sec.</td>
   <td>Group</td>
   <td colspan="4">Name</td>
           @if($class=='Ten')
           <td></td>
           @else
              @if(empty(matchsubcode($student1->sub11code,$tags))) <td></td> @else 
               <td> {{ substr($student1->sub11n,0,3) }} </td>
              @endif
           @endif 

           @if(empty(matchsubcode($student1->sub12code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub12n,0,3)}} </td>
           @endif 

           @if($class=='Ten')
           <td></td>
           @else
           @if(empty(matchsubcode($student1->sub13code,$tags))) <td></td>@else 
            <td> {{ substr($student1->sub13n,0,3)}}  </td>
           @endif 
           @endif

           @if(empty(matchsubcode($student1->sub14code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub14n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub15code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub15n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub16code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub16n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub17code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub17n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub18code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub18n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub19code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub19n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub20code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub20n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub21code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub21n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub22code,$tags))) <td></td> @else 
            <td> {{ substr($student1->sub22n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub23code,$tags))) <td></td> @else 
            <td> {{substr($student1->sub23n,0,3)}}  </td>
           @endif 

           @if(empty(matchsubcode($student1->sub24code,$tags))) <td></td> @else 
            <td> {{substr($student1->sub24n,0,3)}} </td>
           @endif 
     
        <td>T fail</td>
        <td>Total</td>
        <td>Gpa</td>
        <td>Grade</td>
        <td>HGp</td>
        <td>HGr</td>
        <td>CGp</td>
        <td>CGr</td>
  
        <td>Pos.</td>
        <td>Result</td>
  </tr>


@foreach($student as $row)

<tr>
   <td colspan="2">{{studentinfo($row->uid,'stu_id')}} </td>
   <td> {{studentinfo($row->uid,'roll')}} </td>
   <td>{{$row->section}}</td>
   <td><?php echo substr($row->babu,0,4); ?></td>
   <td colspan="4"> <?php echo substr(studentinfo($row->uid,'name'),0,15); ?></td>
   @if(empty(matchsubcode($row->sub11code,$tags))) <td></td> @else 
           <td> {{$row->sub11gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub12code,$tags))) <td></td> @else 
           <td> {{$row->sub12gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub13code,$tags))) <td></td>@else 
           <td> {{$row->sub13gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub14code,$tags))) <td></td> @else 
           <td> {{$row->sub14gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub15code,$tags))) <td></td> @else 
           <td> {{$row->sub15gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub16code,$tags))) <td></td> @else 
           <td> {{$row->sub16gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub17code,$tags))) <td></td> @else 
           <td> {{$row->sub17gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub18code,$tags))) <td></td> @else 
           <td> {{$row->sub18gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub19code,$tags))) <td></td> @else 
           <td> {{$row->sub19gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub20code,$tags))) <td></td> @else 
           <td> {{$row->sub20gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub21code,$tags))) <td></td> @else 
           <td> {{$row->sub21gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub22code,$tags))) <td></td> @else 
           <td> {{$row->sub22gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub23code,$tags))) <td></td> @else 
           <td> {{$row->sub23gp}} </td>
           @endif 

           @if(empty(matchsubcode($row->sub24code,$tags))) <td></td> @else 
           <td> {{$row->sub24gp}} </td>
           @endif 
   <td>{{$row->tfail}}</td>
   <td>{{$row->total}}</td>
   <td>{{$row->gpa}}</td>
   <td>{{$row->g}}</td>
   @if($row->rs==2)
   <td>{{$row->fgp}}</td>
   <td>{{$row->fg}}</td>
   <td>{{$row->cgp}}</td>
   <td>{{$row->cg}}</td>
   @else
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   @endif
   <td>{{$row->position}}</td>
   <td>{{$row->result}}</td>
</tr>

@endforeach

</table>



 


    
     

     

    

      


    

     

     





</body>
</html>


