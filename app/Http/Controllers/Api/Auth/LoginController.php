<?php 

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



Class LoginController extends Controller{

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

 public function login(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
            'code' => 422
        ], 422);
    }

    if (!$token = Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'status' => 'Failed',
            'error' => 'Unauthorized',
            'code' => 401
        ], 401);
    }

    return response()->json([
        'status' => 'True',
        'message' => 'Login Successfully',
        'access_token' => $token,
        'data' => [
            'email' => $request->email
        ],
        'code' => 200
    ]);
 }


}