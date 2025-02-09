<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    /**
     * @view JsonResponse
     */

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
