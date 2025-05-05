<?php

namespace App\Http\Controllers\SchoolPanel\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\SectionService\SectionAdd;
use App\Services\SectionService\SectionList;
use App\Services\SectionService\SectionUpdate;
use App\Services\SectionService\SectionDelete;

class SectionController extends Controller
{

    protected $SectionAdd;
    protected $SectionList;
    protected $SectionUpdate;
    protected $SectionDelete;


    public function __construct(SectionAdd $SectionAdd, SectionList $SectionList, SectionUpdate $SectionUpdate, SectionDelete $SectionDelete)
    {
         $this->SectionAdd = $SectionAdd;
         $this->SectionList = $SectionList;
         $this->SectionUpdate = $SectionUpdate;
         $this->SectionDelete = $SectionDelete;
    }

  
     public function section_add(Request $request,$school_username)
     {
          return $this->SectionAdd->handle($request,$school_username);
     }

     public function section(Request $request,$school_username){
           return $this->SectionList->handle($request,$school_username);
     }

      public function section_update(Request $request,$school_username, $id)
      {
          return $this->SectionUpdate->handle($request,$school_username,$id);
      }
   
 
       public function section_delete(Request $request,$school_username, $id)
       {
           return $this->SectionDelete->handle($request ,$school_username , $id);
       }




}
