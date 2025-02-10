<?php
namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class RegistrController extends Controller{

    /**
     * @view JsonResponse
     */

     public function Regitration(Request $request){

        try{
            $validator = Validator::make($request->all(), [
                'fastName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|numeric|unique:users,phone',
                'password' => 'required|string|min:6|confirmed',
            ]);
            if ($validator->fails()) {
              return response()->json([
                'status' => 'Failed',
                'message' => 'Validation Error',
                'errors' => $validator->errors()
              ], 422);
            }

            $user = User::create([
                'fastName'=>$request->input('fastName'),
                'lastName'=>$request->input('lastName'),
                'email'=>$request->input('email'),
                'phone'=>$request->input('phone'),
                'password' => bcrypt($request->input('password')),
                
            ]);


            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'status'=>'true',
                'message'=>'User Successfully Regitration',
                'code'=>201,
                'access_token' => $token,
                'data' => [
                'fastName' => $user->fastName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            ],201);


        }catch(Exception $e){

            return response()->json([
                'status'=>'Failed',
                'message'=>'User Regitration Failled',
            ],404);

        };
        
    }

}