<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentController extends Controller
{

    public function add_agent(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Agent added successfully',
            'status' =>$request->all(),
        ]);
         die();
        $validator = validator($request->all(), [           
            'name' => 'required',
            'email'=>'required|unique:members,email',
            'phone'=>'required|unique:members,phone',
            'password'=>'required|regex:/^[a-zA-Z\d]*$/',
            'address'=>'required',
            'username'=>'required',
            'district'=>'required',
            'upazila'=>'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    

        return response()->json([
            'status' => 'success',
            'message' => 'Agent added successfully',
            'status' =>$request->all(),
        ]);
    }
}
