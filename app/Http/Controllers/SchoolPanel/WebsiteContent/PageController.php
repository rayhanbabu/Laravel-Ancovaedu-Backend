<?php

namespace App\Http\Controllers\SchoolPanel\WebsiteContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PageService\PageAdd;
use App\Services\PageService\PageList;
use App\Services\PageService\PageUpdate;
use App\Services\PageService\PageDelete;



class PageController extends Controller
{

    protected $PageAdd;
    protected $PageList;
    protected $PageUpdate;
    protected $PageDelete;


    public function __construct(PageAdd $PageAdd, PageList $PageList, PageUpdate $PageUpdate 
    , PageDelete $PageDelete)
    {
         $this->PageAdd = $PageAdd;
         $this->PageList = $PageList;
         $this->PageUpdate = $PageUpdate;
         $this->PageDelete = $PageDelete;      
    }


     public function page_add(Request $request,$school_username)
     {
          return $this->PageAdd->handle($request,$school_username);
     }


     public function page(Request $request,$school_username){
           return $this->PageList->handle($request,$school_username);
     }

      public function page_update(Request $request,$school_username, $id)
      {
          return $this->PageUpdate->handle($request,$school_username,$id);
      }


       public function page_delete(Request $request,$school_username, $id)
       {
           return $this->PageDelete->handle($request ,$school_username , $id);
       }


}
