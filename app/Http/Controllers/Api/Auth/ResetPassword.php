<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPassword extends Controller{
/**
     * Reset user password after OTP verification.
     */
    // public function resetPassword(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email|exists:users,email',
    //             'password' => 'required|string|min:6'
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 'Failed',
    //                 'message' => 'Validation Error',
    //                 'errors' => $validator->errors(),
    //                 'code' => 422
    //             ], 422);
    //         }
    
    //         $user = User::where('email', $request->email)
    //             ->first();
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => 'Failed',
    //                 'message' => 'Invalid OTP or user not found',
    //                 'code' => 401
    //             ], 401);
    //         }
    
    //         $user->password = Hash::make($request->password);
    //         $user->save();
    
    //         return response()->json([
    //             'status' => 'Success',
    //             'message' => 'Password reset successfully. You can now log in.',
    //             'code' => 200
    //         ], 200);
    
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 'Failed',
    //             'message' => 'An error occurred while resetting the password',
    //             'error' => $e->getMessage(),
    //             'code' => 500
    //         ], 500);
    //     }
    // }

    public function resetPassword(Request $request)
{
    try {
        // Validate email and password
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'code' => 422
            ], 422);
        }

        // Get OTP from request header
        $otp = $request->header('OTP');

        if (!$otp) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'OTP is required in the header',
                'code' => 400
            ], 400);
        }

        // Find user by email and match OTP
        $user = User::where('email', $request->email)
            ->where('otp', $otp)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid OTP or user not found',
                'code' => 401
            ], 401);
        }

        // Reset password
        $user->password = Hash::make($request->password);
        $user->otp = '0'; 
        $user->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Password reset successfully. You can now log in.',
            'code' => 200
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'An error occurred while resetting the password',
            'error' => $e->getMessage(),
            'code' => 500
        ], 500);
    }
}

}