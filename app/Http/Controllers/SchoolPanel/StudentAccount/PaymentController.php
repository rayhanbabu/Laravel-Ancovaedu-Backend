<?php

namespace App\Http\Controllers\SchoolPanel\StudentAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Services\PaymentService\PaymentAdd;
use App\Services\PaymentService\PaymentList;
use App\Services\PaymentService\PaymentDelete;
use App\Services\PaymentService\PaymentReport;
use App\Services\PaymentService\PartialPaymentDelete;



class PaymentController extends Controller
{

    protected $PaymentAdd;
    protected $PaymentList;
    protected $PaymentDelete;
    protected $PaymentReport;
    protected $PartialPaymentDelete;


   
    public function __construct(PaymentAdd $PaymentAdd, PaymentList $PaymentList,PaymentDelete $PaymentDelete
     , PaymentReport $PaymentReport, PartialPaymentDelete $PartialPaymentDelete)
    {
         $this->PaymentAdd = $PaymentAdd;
         $this->PaymentList = $PaymentList;
         $this->PaymentDelete = $PaymentDelete;
         $this->PaymentReport = $PaymentReport;
         $this->PartialPaymentDelete = $PartialPaymentDelete;


       
    }

  
     public function payment_add(Request $request,$school_username)
       {
           return $this->PaymentAdd->handle($request,$school_username);
       }


     public function payment(Request $request,$school_username){
           return $this->PaymentList->handle($request,$school_username);
     }

     public function payment_report(Request $request,$school_username){
        return $this->PaymentReport->handle($request,$school_username);
  }

     
   
 
       public function payment_delete(Request $request,$school_username, $id)
       {
           return $this->PaymentDelete->handle($request ,$school_username , $id);
       }

         public function partial_payment_delete(Request $request,$school_username, $id)
         {
              return $this->PartialPaymentDelete->handle($request ,$school_username , $id);
         }

     

}
