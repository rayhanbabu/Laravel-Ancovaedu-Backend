<?php

namespace App\Http\Controllers\SchoolPanel\WebsiteContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\GpaListService\GpaListAdd;
use App\Services\GpaListService\GpaListList;
use App\Services\GpaListService\GpaListUpdate;
use App\Services\GpaListService\GpaListDelete;



class GpaListController extends Controller
{

    protected $GpaListAdd;
    protected $GpaListList;
    protected $GpaListUpdate;
    protected $GpaListDelete;


    public function __construct(GpaListAdd $GpaListAdd, GpaListList $GpaListList, GpaListUpdate $GpaListUpdate 
    , GpaListDelete $GpaListDelete)
    {
         $this->GpaListAdd = $GpaListAdd;
         $this->GpaListList = $GpaListList;
         $this->GpaListUpdate = $GpaListUpdate;
         $this->GpaListDelete = $GpaListDelete;      
    }


     public function gpaList_add(Request $request,$school_username)
     {
          return $this->GpaListAdd->handle($request,$school_username);
     }


     public function gpaList(Request $request,$school_username){
           return $this->GpaListList->handle($request,$school_username);
     }

      public function gpaList_update(Request $request,$school_username, $id)
      {
          return $this->GpaListUpdate->handle($request,$school_username,$id);
      }


       public function gpaList_delete(Request $request,$school_username, $id)
       {
           return $this->GpaListDelete->handle($request ,$school_username , $id);
       }


}
