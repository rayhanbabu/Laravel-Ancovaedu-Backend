<?php

namespace App\Http\Controllers\SchoolPanel\StudentAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\InvoiceService\InvoiceAdd;
use App\Services\InvoiceService\InvoiceList;
use App\Services\InvoiceService\InvoiceSingleAdd;
use App\Services\InvoiceService\InvoiceDelete;
use App\Services\InvoiceService\InvoiceCustomAdd;
use App\Services\InvoiceService\InvoiceGroupDelete;







class InvoiceController extends Controller
{

    protected $InvoiceAdd;
    protected $InvoiceList;
    protected $InvoiceSingleAdd;
    protected $InvoiceDelete;
    protected $InvoiceCustomAdd;
    protected $InvoiceGroupDelete;

   

    public function __construct(InvoiceAdd $InvoiceAdd, InvoiceList $InvoiceList, InvoiceSingleAdd $InvoiceSingleAdd,
          InvoiceDelete $InvoiceDelete, InvoiceCustomAdd $InvoiceCustomAdd,
          InvoiceGroupDelete $InvoiceGroupDelete)
    {
         $this->InvoiceAdd = $InvoiceAdd;
         $this->InvoiceList = $InvoiceList;
         $this->InvoiceSingleAdd = $InvoiceSingleAdd;
         $this->InvoiceDelete = $InvoiceDelete;
         $this->InvoiceCustomAdd = $InvoiceCustomAdd;
         $this->InvoiceGroupDelete = $InvoiceGroupDelete;


       
    }

  
     public function invoice_add(Request $request,$school_username)
       {
           return $this->InvoiceAdd->handle($request,$school_username);
       }


     public function invoice(Request $request,$school_username){
           return $this->InvoiceList->handle($request,$school_username);
     }

      public function invoice_single_add(Request $request,$school_username)
      {
          return $this->InvoiceSingleAdd->handle($request,$school_username);
      }

         public function invoice_custom_add(Request $request,$school_username)
         {
              return $this->InvoiceCustomAdd->handle($request,$school_username);
         }
   
 
        public function invoice_delete(Request $request,$school_username, $id)
        {
           return $this->InvoiceDelete->handle($request ,$school_username , $id);
        }


       public function invoice_group_delete(Request $request,$school_username, $fee_id)
        {
            return $this->InvoiceGroupDelete->handle($request ,$school_username , $fee_id);
        }

     

}
