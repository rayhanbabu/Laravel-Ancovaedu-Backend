<?php

namespace App\Http\Controllers\SchoolPanel\Mark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


use App\Services\MarkService\MarkList;
use App\Services\MarkService\MarkUpdate;
use App\Services\MarkService\MarkSubmit;


class MarkController extends Controller
{

    protected $MarkList;
    protected $MarkUpdate;
    protected $MarkSubmit;
   

    public function __construct( MarkList $MarkList, MarkUpdate $MarkUpdate,
     MarkSubmit $MarkSubmit)
    {
     
         $this->MarkList = $MarkList;
         $this->MarkUpdate = $MarkUpdate;
         $this->MarkSubmit = $MarkSubmit;
       
    }

  
      public function mark(Request $request,$school_username){
            return $this->MarkList->handle($request,$school_username);
      }

      public function mark_update(Request $request,$school_username)
       {
           return $this->MarkUpdate->handle($request,$school_username);
       }
   
 
       public function mark_final_submit(Request $request,$school_username)
        {
            return $this->MarkSubmit->handle($request ,$school_username);
        }

     

}
