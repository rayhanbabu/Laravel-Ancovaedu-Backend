<?php

namespace App\Http\Controllers\SchoolPanel\AdmitCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\AdmitCardService\AdmitCardAdd;
use App\Services\AdmitCardService\AdmitCardList;
use App\Services\AdmitCardService\AdmitCardUpdate;
use App\Services\AdmitCardService\AdmitCardDelete;




class AdmitCardController extends Controller
{

    protected $AdmitCardAdd;
    protected $AdmitCardList;
    protected $AdmitCardUpdate;
    protected $AdmitCardDelete;
   

    public function __construct(AdmitCardAdd $AdmitCardAdd, AdmitCardList $AdmitCardList, AdmitCardUpdate $AdmitCardUpdate,
     AdmitCardDelete $AdmitCardDelete)
    {
         $this->AdmitCardAdd = $AdmitCardAdd;
         $this->AdmitCardList = $AdmitCardList;
         $this->AdmitCardUpdate = $AdmitCardUpdate;
         $this->AdmitCardDelete = $AdmitCardDelete;
       
    }

  
     public function admitcard_add(Request $request,$school_username)
       {
           return $this->AdmitCardAdd->handle($request,$school_username);
       }


     public function admitcard(Request $request,$school_username){
           return $this->AdmitCardList->handle($request,$school_username);
     }

      public function admitcard_update(Request $request,$school_username)
      {
          return $this->AdmitCardUpdate->handle($request,$school_username);
      }
   
 
       public function admitcard_delete(Request $request,$school_username, $id)
       {
           return $this->AdmitCardDelete->handle($request ,$school_username, $id );
       }

     

}
