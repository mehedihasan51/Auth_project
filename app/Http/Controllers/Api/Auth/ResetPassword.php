<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPassword extends Controller{
/**
* Reset user password after OTP verification.
*/
    public function resetPassword(Request $request)
      {
      try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'code' => 422
            ], 422);
        }

        $user = User::where('email', $request->email)
                  ->where('otp',$request->otp)
                  ->first();
 
        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid OTP or user not found',
                'code' => 401
            ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null; 
        $user->save();
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'status' => 'Success',
            'message' => 'Password reset successfully. You can now log in.',
            'access_token' => $token,
            'code' => 200
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'An error occurred while resetting the password',
            'code' => 500
        ], 500);
    }
}


}