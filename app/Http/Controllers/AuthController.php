<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $key = 'login_attempts:' . $request->ip();
    
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Too many login attempts. Please try again later.'
            ], 429);
        }
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['Invalid credentials']
                ]
            ], 422);
        }
    
        RateLimiter::clear($key);
        
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;
    
        return response()->json([
             'message' => 'Login successful',
             'token' => $token,
             'data' =>user(),
          ], 200);
      }

    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' =>user(),
            'fuction' =>rayhan()
        ],200);
    }

     public function manager_list(Request $request)
     {
            $query = User_role::query();
            $query->where('role_type','Manager');
            $data = $query->get();

             return response()->json([
                 'status' => 'success',
                 'data' => $data
            ],200);
      }
    

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'],200);
    }
}
