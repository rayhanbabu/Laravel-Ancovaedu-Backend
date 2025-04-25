<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\LevelService\LevelAdd;
use App\Services\LevelService\LevelList;
use App\Services\LevelService\LevelUpdate;
use App\Services\LevelService\LevelDelete;

class LevelController extends Controller
{

    protected $LevelAdd;
    protected $LevelList;
    protected $LevelUpdate;
    protected $LevelDelete;


    public function __construct(LevelAdd $LevelAdd, LevelList $LevelList, LevelUpdate $LevelUpdate, LevelDelete $LevelDelete)
    {
         $this->LevelAdd = $LevelAdd;
         $this->LevelList = $LevelList;
         $this->LevelUpdate = $LevelUpdate;
         $this->LevelDelete = $LevelDelete;
    }

  
     public function level_add(Request $request,$school_username)
     {
          return $this->LevelAdd->handle($request,$school_username);
     }

     public function level(Request $request,$school_username){
           return $this->LevelList->handle($request,$school_username);
     }

      public function level_update(Request $request,$school_username, $id)
      {
          return $this->LevelUpdate->handle($request,$school_username,$id);
      }
   
 
       public function level_delete(Request $request,$school_username, $id)
       {
           return $this->LevelDelete->handle($request ,$school_username , $id);
       }




}
