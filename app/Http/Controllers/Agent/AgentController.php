<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_role;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AgentResource;
use Illuminate\Support\Facades\File;

use App\Services\AgentService\AgentAdd;
use App\Services\AgentService\AgentList;
use App\Services\AgentService\AgentUpdate;
use App\Services\AgentService\AgentStatus;
use App\Services\AgentService\AgentDelete;

class AgentController extends Controller
{

    protected $agentAdd;
    protected $agentList;
    protected $agentUpdate;
    protected $agentStatus;
    protected $agentDelete;


    public function __construct(AgentAdd $agentAdd, AgentList $agentList, AgentUpdate $agentUpdate, AgentStatus $agentStatus, AgentDelete $agentDelete)
    {
        $this->agentAdd = $agentAdd;
        $this->agentList = $agentList;
        $this->agentUpdate = $agentUpdate;
        $this->agentStatus = $agentStatus;
        $this->agentDelete = $agentDelete;
    }

  
     public function add_agent(Request $request)
     {
         return $this->agentAdd->handle($request);
     }

     public function agent(Request $request){
           return $this->agentList->handle($request);
     }

      public function agent_update(Request $request, $id)
      {
          return $this->agentUpdate->handle($request, $id);
      }
   
      public function agent_status(Request $request)
       {
           return $this->agentStatus->handle($request);
       }
   

       public function agent_delete(Request $request, $id)
       {
           return $this->agentDelete->handle($request, $id);
       }




}
