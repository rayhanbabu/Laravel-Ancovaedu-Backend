<?php

namespace App\Http\Controllers\SchoolPanel\WebsiteContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PageCategoryService\PageCategoryAdd;
use App\Services\PageCategoryService\PageCategoryList;
use App\Services\PageCategoryService\PageCategoryUpdate;
use App\Services\PageCategoryService\PageCategoryDelete;



class PageCategoryController extends Controller
{

    protected $PageCategoryAdd;
    protected $PageCategoryList;
    protected $PageCategoryUpdate;
    protected $PageCategoryDelete;


    public function __construct(PageCategoryAdd $PageCategoryAdd, PageCategoryList $PageCategoryList, PageCategoryUpdate $PageCategoryUpdate 
    , PageCategoryDelete $PageCategoryDelete)
    {
         $this->PageCategoryAdd = $PageCategoryAdd;
         $this->PageCategoryList = $PageCategoryList;
         $this->PageCategoryUpdate = $PageCategoryUpdate;
         $this->PageCategoryDelete = $PageCategoryDelete;      
    }


     public function pageCategory_add(Request $request,$school_username)
     {
          return $this->PageCategoryAdd->handle($request,$school_username);
     }


     public function pageCategory(Request $request,$school_username){
           return $this->PageCategoryList->handle($request,$school_username);
     }

      public function pageCategory_update(Request $request,$school_username, $id)
      {
          return $this->PageCategoryUpdate->handle($request,$school_username,$id);
      }


       public function pageCategory_delete(Request $request,$school_username, $id)
       {
           return $this->PageCategoryDelete->handle($request ,$school_username , $id);
       }


}
