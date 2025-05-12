<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PermissionService\PermissionAdd;
use App\Services\PermissionService\PermissionList;
use App\Services\PermissionService\PermissionUpdate;
use App\Services\PermissionService\PermissionDelete;




class EmployeePermissionController extends Controller
{

    protected $PermissionAdd;
    protected $PermissionList;
    protected $PermissionUpdate;
    protected $PermissionDelete;
   

    public function __construct(PermissionAdd $PermissionAdd, PermissionList $PermissionList, PermissionUpdate $PermissionUpdate,
     PermissionDelete $PermissionDelete)
    {
         $this->PermissionAdd = $PermissionAdd;
         $this->PermissionList = $PermissionList;
         $this->PermissionUpdate = $PermissionUpdate;
         $this->PermissionDelete = $PermissionDelete;   
    }

  
     public function permission_add(Request $request,$school_username){
           return $this->PermissionAdd->handle($request,$school_username);
       }


     public function permission(Request $request,$school_username){
           return $this->PermissionList->handle($request,$school_username);
     }

      public function permission_update(Request $request,$school_username, $id)
      {
          return $this->PermissionUpdate->handle($request,$school_username,$id);
      }
   
 
       public function permission_delete(Request $request,$school_username, $id)
       {
           return $this->PermissionDelete->handle($request ,$school_username , $id);
       }


         public function permission_role(Request $request,$school_username)
         {
              $data=Permission::get();
                 return response()->json([
                       'data' =>$data
                 ]);
          }

     

}
