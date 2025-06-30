<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

*{
  box-sizing: border-box;
  color:{{$color->color13}};
}

  .border{
   border: 5px double {{$color->color14}} ;
  }
  
  .vl{
       border-left: 5px double {!!$color->color14!!};
       height: 630px;
      }   
  
  .boldheader{
	border:3px double red;
  }
.row::after {
  content: "";
  clear: both;
  display: table; 
}

[class*="col-"] {
  float: left;
  padding: 10px;
}

.col-1 {width: 8.33%;}
.col-2 {width: 16.66%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%;}
.col-5 {width: 41.66%;}
.col-6 {width: 50%;}
.col-7 {width: 58.33%;}
.col-8 {width: 66.66%;}
.col-9 {width: 75%;}
.col-10 {width: 83.33%;}
.col-11 {width: 91.66%;}
.col-12 {width: 100%;}
.col-13 {width: 1.00%;}

  html{
      font-family: "Lucida Sans", sans-serif;
   }

     #school{
		   font-size:{{$school->test3}}px;
		   text-align:center;  
		 
	   }

     #up{
		 font-size:{{$school->test4}}px;
		 text-align:center;
         
	   }

     #school1{    
		   font-size:{{$school->test1}}px;
		   text-align:center;
		    margin-right:-110px;
           
	   }

     #up1{
		 font-size:{{$school->test2}}px;
		 text-align:center;
		  margin-right:-110px;
	   }

     #header{
		   font-size:22px;
		   text-align:center;
	  }

      #text{
	      padding:5px;
		  font-size:16px;
	   }	 
     
     .rayhan {
     position: absolute;
     left:465px;
    top:150px;
    z-index: -1;
     opacity: .2;
  }

   .headerlogo{
    	position: absolute;
       left:320px;
       top:40px;
      z-index: -1;
      opacity: .9;
   }

.page-break{
          page-break-after:always;
       }

</style>
</head>
<body>
@foreach($exstudent as $row)
<div class="rayhan">
    <img src="{{ public_path("/uploads/admin/".$school->image) }}" style="width:400px; height:400px;"/>
  </div>
  
  <div class="headerlogo">
    <img src="{{ public_path("/uploads/admin/".$school->image) }}" style="width:100px; height:100px;"/>
  </div>

  <div class="border">
  <div class="row">
    <div class="col-3">
         <p id="school"> {{$school->school}} </p>
		   <p id="up">{!!$school->address_details!!}</p>
		 
		       <p id="header"> <b class="boldheader">TESTIMONIAL</b></p>
	    
           <p id="text">Name <b>{{$row->name}}</b>,Father's Name <b>{{$row->father}}</b> 
		      Mother's Name <b>{{$row->mother}}</b>  , <b>{{$row->address}}</b>
			   Group <b>  {{$row->babu}}</b> , G.P.A Point <b>{{$row->sgp}}</b>, Roll No 
			  <b>{{$row->sroll}}</b>, Registration No <b>{{$row->sreg}}</b>, Session <b>{{$row->syear}}</b>
              .His/Her date of birth  <b><?php echo date('d-F-Y',strtotime($row['birth_date'])) ?></b>.
		   </p>
		   <br><br>
		    <p align="right"> Head teacher Signature</p>
     
	 </div>
	
	  <div class="col-13">
	     <div class="vl"></div>
	 </div>
  
       <div class="col-8">
           <p id="school1"> {{strtoupper($school->school)}}</p> 
		   <p id="up1">{!!strtoupper($school->address_details)!!}</p>
		 
		     <p id="header"> <b class="boldheader">TESTIMONIAL</b></p>
			 
	
      <p id="text"> Certified that <b>{{strtoupper($row->name)}}</b>, Father's Name:<b>{{strtoupper($row->father)}}</b>, 
	  Mother's Name:<b>{{strtoupper($row->mother)}}</b>,<b>{{strtoupper($row->address)}}</b>
	  Was a student of this Institution.He/She appeared at secondary and higher secondary education 
	  board,<b>{{strtoupper($row->sboard)}}</b> from group <b> {{strtoupper($row->babu)}}</b> hold in the year <b>{{$row->year}}</b>
	  and obtained G.P.A Point <b>{{strtoupper($row->sgp)}}</b> and  grade <b>{{strtoupper($row->sg)}}</b>.His/Her Roll No 
	  <b>{{$row->sroll}}</b>, Registration No <b>{{$row->sreg}}</b> of the session  <b>{{$row->syear}}</b> and
	  date of birth <?php echo date('d-F-Y',strtotime($row['birth_date'])) ?>.In Word <b> <?php echo strtoupper(convertNumber(date('j',strtotime($row['birth_date'])))) ?>
	  <?php echo strtoupper(date('F',strtotime($row['birth_date']))) ?> <?php echo strtoupper(convertNumber(date('Y',strtotime($row['birth_date'])))) ?> </b>
		   </p>

		   <p id="text">
		      During his/her studies in this Institution his/her did not take part in activities
			  subversive of the state of discipline. <br><br>

			  I wish his/her every success in life.
		   </p>
		   
		   
		    <br><br>
		   <p align="right"> Head teacher Signature</p>
     </div>
   </div>
	</div>
 
   <div class="page-break"></div>
@endforeach  





</body>
</html>


