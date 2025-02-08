<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    /**
     * @view JsonResponse
     */

    public function UserRegitration(Request $request){

        try{

            $user = User::create([
                'fastName'=>$request->input('fastName'),
                'lastName'=>$request->input('lastName'),
                'email'=>$request->input('email'),
                'phone'=>$request->input('phone'),
                'password' => bcrypt($request->input('password')),
                
            ]);
    
            return response()->json([
                'status'=>'true',
                'message'=>'User Successfully Regitration',
                'code'=>201,
                'data' => $user,
            ],201);


        }catch(Exception $e){

            return response()->json([
                'status'=>'Failed',
                'message'=>'User Regitration Failled',
            ],404);

        };
        
    }

    /**
     * @view JsonResponse
     */

    public function UserLogin(Request $request)
       {

       $user = User::where('email', $request->input('email'))->first();

         if ($user && Hash::check($request->input('password'), $user->password)) {
        
          $token = JWTToken::createToken($user->email);

          return response()->json([
            'status' => 'Successful',
            'message' => 'User Login Successful',
            'token' => $token
          ], 200);
        } else {
         return response()->json([
            'status' => 'Failed',
            'message' => 'Unauthorized'
         ], 401);
       }
     }

    public function SendOTPCode(Request $request)
{
    try {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ], 401);
        }

        Mail::to($email)->send(new OTPMail($otp));

        $user->update(['otp' => $otp]);

        return response()->json([
            'status' => 'Success',
            'message' => '4-digit OTP Successfully Sent'
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'OTP Sending Failed',
            'error' => $e->getMessage() // Debugging message
        ], 500);
    }
}
   }
