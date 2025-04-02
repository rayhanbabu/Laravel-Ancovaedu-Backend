<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ManagerResource;
use Illuminate\Support\Facades\File;

use App\Services\ManagerService\ManagerAdd;
use App\Services\ManagerService\ManagerList;
use App\Services\ManagerService\ManagerUpdate;
use App\Services\ManagerService\ManagerStatus;
use App\Services\ManagerService\ManagerDelete;

class ManagerController extends Controller
{

    protected $managerAdd;
    protected $managerList;
    protected $managerUpdate;
    protected $managerStatus;
    protected $managerDelete;


    public function __construct(ManagerAdd $managerAdd, ManagerList $managerList, ManagerUpdate $managerUpdate, ManagerStatus $managerStatus, ManagerDelete $managerDelete)
    {
        $this->managerAdd = $managerAdd;
        $this->managerList = $managerList;
        $this->managerUpdate = $managerUpdate;
        $this->managerStatus = $managerStatus;
        $this->managerDelete = $managerDelete;
    }

  
     public function manager_add(Request $request)
     {
         return $this->managerAdd->handle($request);
     }

     public function manager(Request $request){
           return $this->managerList->handle($request);
     }

      public function manager_update(Request $request, $id)
      {
          return $this->managerUpdate->handle($request, $id);
      }
   
      public function manager_status(Request $request)
       {
           return $this->managerStatus->handle($request);
       }
   

       public function manager_delete(Request $request, $id)
       {
           return $this->managerDelete->handle($request, $id);
       }




}
