<?php

namespace App\Http\Controllers\SchoolPanel\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\EmployeeService\EmployeeAdd;
use App\Services\EmployeeService\EmployeeList;
use App\Services\EmployeeService\EmployeeUpdate;
use App\Services\EmployeeService\EmployeeDelete;


class EmployeeController extends Controller
{

    protected $EmployeeAdd;
    protected $EmployeeList;
    protected $EmployeeUpdate;
    protected $EmployeeDelete;


    public function __construct(EmployeeAdd $EmployeeAdd, EmployeeList $EmployeeList, EmployeeUpdate $EmployeeUpdate 
    , EmployeeDelete $EmployeeDelete)
    {
         $this->EmployeeAdd = $EmployeeAdd;
         $this->EmployeeList = $EmployeeList;
         $this->EmployeeUpdate = $EmployeeUpdate;
         $this->EmployeeDelete = $EmployeeDelete;      
    }

  
     public function employee_add(Request $request,$school_username)
     {
          return $this->EmployeeAdd->handle($request,$school_username);
     }


     public function employee(Request $request,$school_username){
           return $this->EmployeeList->handle($request,$school_username);
     }

      public function employee_update(Request $request,$school_username, $id)
      {
          return $this->EmployeeUpdate->handle($request,$school_username,$id);
      }
   
 
       public function employee_delete(Request $request,$school_username, $id)
       {
           return $this->EmployeeDelete->handle($request ,$school_username , $id);
       }

      
    
}
