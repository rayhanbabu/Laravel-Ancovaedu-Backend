<?php

namespace App\Http\Controllers\SchoolPanel\SchoolAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BalanceResource;
use Illuminate\Support\Facades\File;

use App\Services\BalanceService\BalanceAdd;
use App\Services\BalanceService\BalanceList;
use App\Services\BalanceService\BalanceUpdate;
use App\Services\BalanceService\BalanceStatus;
use App\Services\BalanceService\BalanceDelete;

class BalanceController extends Controller
{

    protected $BalanceAdd;
    protected $BalanceList;
    protected $BalanceUpdate;
    protected $BalanceStatus;
    protected $BalanceDelete;


    public function __construct(BalanceAdd $BalanceAdd, BalanceList $BalanceList, BalanceUpdate $BalanceUpdate, BalanceStatus $BalanceStatus, BalanceDelete $BalanceDelete)
    {
        $this->BalanceAdd = $BalanceAdd;
        $this->BalanceList = $BalanceList;
        $this->BalanceUpdate = $BalanceUpdate;
        $this->BalanceStatus = $BalanceStatus;
        $this->BalanceDelete = $BalanceDelete;
    }

  
     public function balance_add(Request $request)
     {
          return $this->BalanceAdd->handle($request);
     }

     
     public function balance(Request $request){
           return $this->BalanceList->handle($request);
     }


      public function balance_update(Request $request ,$school_username, $id)
      {
           return $this->BalanceUpdate->handle($request,$school_username,$id);
      }
   

      public function balance_status(Request $request,$school_username, $id)
       {
            return $this->BalanceStatus->handle($request,$school_username, $id);
       }
   

       public function balance_delete(Request $request ,$school_username,$id)
       {
             return $this->BalanceDelete->handle($request ,$school_username ,$id);
       }




}
