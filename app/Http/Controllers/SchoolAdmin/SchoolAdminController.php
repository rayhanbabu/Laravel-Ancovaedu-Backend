<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\User_role;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SchoolAdminResource;
use Illuminate\Support\Facades\File;

use App\Services\SchoolAdminService\SchoolAdminAdd;
use App\Services\SchoolAdminService\SchoolAdminList;
use App\Services\SchoolAdminService\SchoolAdminUpdate;
use App\Services\SchoolAdminService\SchoolAdminStatus;
use App\Services\SchoolAdminService\SchoolAdminDelete;

class SchoolAdminController extends Controller
{

    protected $SchoolAdminAdd;
    protected $SchoolAdminList;
    protected $SchoolAdminUpdate;
    protected $SchoolAdminStatus;
    protected $SchoolAdminDelete;


    public function __construct(SchoolAdminAdd $SchoolAdminAdd, SchoolAdminList $SchoolAdminList, SchoolAdminUpdate $SchoolAdminUpdate, SchoolAdminStatus $SchoolAdminStatus, SchoolAdminDelete $SchoolAdminDelete)
    {
        $this->SchoolAdminAdd = $SchoolAdminAdd;
        $this->SchoolAdminList = $SchoolAdminList;
        $this->SchoolAdminUpdate = $SchoolAdminUpdate;
        $this->SchoolAdminStatus = $SchoolAdminStatus;
        $this->SchoolAdminDelete = $SchoolAdminDelete;
    }

  
     public function schooladmin_add(Request $request)
     {
         return $this->SchoolAdminAdd->handle($request);
     }

     public function schooladmin(Request $request){
           return $this->SchoolAdminList->handle($request);
     }

      public function schooladmin_update(Request $request, $id)
      {
          return $this->SchoolAdminUpdate->handle($request, $id);
      }
   
      public function schooladmin_status(Request $request)
       {
           return $this->SchoolAdminStatus->handle($request);
       }
   

       public function schooladmin_delete(Request $request, $id)
       {
           return $this->SchoolAdminDelete->handle($request, $id);
       }




}
