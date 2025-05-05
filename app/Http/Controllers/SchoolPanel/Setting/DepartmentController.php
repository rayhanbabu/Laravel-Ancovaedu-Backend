<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\DepartmentService\DepartmentAdd;
use App\Services\DepartmentService\DepartmentList;
use App\Services\DepartmentService\DepartmentUpdate;
use App\Services\DepartmentService\DepartmentDelete;

class DepartmentController extends Controller
{

    protected $DepartmentAdd;
    protected $DepartmentList;
    protected $DepartmentUpdate;
    protected $DepartmentDelete;


    public function __construct(DepartmentAdd $DepartmentAdd, DepartmentList $DepartmentList, DepartmentUpdate $DepartmentUpdate, DepartmentDelete $DepartmentDelete)
    {
         $this->DepartmentAdd = $DepartmentAdd;
         $this->DepartmentList = $DepartmentList;
         $this->DepartmentUpdate = $DepartmentUpdate;
         $this->DepartmentDelete = $DepartmentDelete;
    }

  
     public function department_add(Request $request,$school_username)
     {
          return $this->DepartmentAdd->handle($request,$school_username);
     }

     public function department(Request $request,$school_username){
           return $this->DepartmentList->handle($request,$school_username);
     }

      public function department_update(Request $request,$school_username, $id)
      {
          return $this->DepartmentUpdate->handle($request,$school_username,$id);
      }
   
 
       public function department_delete(Request $request,$school_username, $id)
       {
           return $this->DepartmentDelete->handle($request ,$school_username , $id);
       }




}
