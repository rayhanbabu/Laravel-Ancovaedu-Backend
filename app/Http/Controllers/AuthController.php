<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User_role;
use App\Models\School;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

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


        public function school_profile(Request $request,$school_username)
        {
              $query = School::query();
              $query->where('school_username',$school_username);
              $query->with('user:id,name,email,phone,username,profile_picture,status');
              $data = $query->first();

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


         public function forget_password(Request $request)
            {
                $validator = validator($request->all(), [
                    'email' => 'required|email',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $email = $request->input('email');
                $user  = User::where('email', $email)->first();

                if (!$user) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User not found'
                    ], 400);
                }

                $resetToken = Str::random(60);

                DB::table('users')
                    ->where('email', $email)
                    ->update([
                        'forget_reset_code' => $resetToken,
                        'forget_reset_time' => now()
                    ]);

                // Send the reset email
                Mail::to($email)->send(new PasswordResetMail($user, $resetToken));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset link sent to your email'
                ], 200);
            }


    public function reset_password(Request $request)
    {
         $validator = validator($request->all(), [          
             'password' => 'required|min:6|confirmed',
             'password_confirmation' => 'required|min:6'
         ]);

          if ($validator->fails()) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Validation failed',
                 'errors' => $validator->errors()
             ], 422);
          }

        $user = User::where('forget_reset_code', $request->forget_reset_code)->first();

         if (!$user && $request->forget_reset_code!=NULL) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token'
            ], 400);
          }

                // Check if token is expired (5 minutes)
            $resetTime = $user->forget_reset_time;
            if (now()->diffInMinutes($resetTime) > 5) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reset token expired'
                ], 400);
            }

            // Update the user's password
            $user->password = bcrypt($request->input('password'));
            $user->forget_reset_code = null; // Clear the reset code
            $user->forget_reset_time = null; // Clear reset time
            $user->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset successfully',
                    'forget_reset_code' => $user,
                ], 200);
            }

}
