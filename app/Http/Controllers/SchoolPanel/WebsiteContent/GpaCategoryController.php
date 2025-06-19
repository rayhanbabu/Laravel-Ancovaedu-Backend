<?php

namespace App\Http\Controllers\SchoolPanel\WebsiteContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\GpaCategoryService\GpaCategoryAdd;
use App\Services\GpaCategoryService\GpaCategoryList;
use App\Services\GpaCategoryService\GpaCategoryUpdate;
use App\Services\GpaCategoryService\GpaCategoryDelete;



class GpaCategoryController extends Controller
{

    protected $GpaCategoryAdd;
    protected $GpaCategoryList;
    protected $GpaCategoryUpdate;
    protected $GpaCategoryDelete;


    public function __construct(GpaCategoryAdd $GpaCategoryAdd, GpaCategoryList $GpaCategoryList, GpaCategoryUpdate $GpaCategoryUpdate 
    , GpaCategoryDelete $GpaCategoryDelete)
    {
         $this->GpaCategoryAdd = $GpaCategoryAdd;
         $this->GpaCategoryList = $GpaCategoryList;
         $this->GpaCategoryUpdate = $GpaCategoryUpdate;
         $this->GpaCategoryDelete = $GpaCategoryDelete;      
    }


     public function gpaCategory_add(Request $request,$school_username)
     {
          return $this->GpaCategoryAdd->handle($request,$school_username);
     }


     public function gpaCategory(Request $request,$school_username){
           return $this->GpaCategoryList->handle($request,$school_username);
     }

      public function gpaCategory_update(Request $request,$school_username, $id)
      {
          return $this->GpaCategoryUpdate->handle($request,$school_username,$id);
      }


       public function gpaCategory_delete(Request $request,$school_username, $id)
       {
           return $this->GpaCategoryDelete->handle($request ,$school_username , $id);
       }


}
