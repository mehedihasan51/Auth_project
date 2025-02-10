<?php 

namespace App\Http\Controllers\Api\Auth;

use Log;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Pest\Laravel\get;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller{

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
                'error' =>$e->getMessage()
            ], 500);
        }
    }

    /**
     * @view JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|numeric'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Validation Error',
                    'code' => 422,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $user = User::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();
    
            if (!$user) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Invalid OTP or user not found',
                    'code' => 401
                ], 401);
            }
            $user->otp = null;
            $user->save();
    
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'status' => 'Success',
                'access_token' => $token,
                'token_type' => 'bearer',
                'message' => 'OTP Verified. You can now reset your password.',
                'code' => 200
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'An error occurred while verifying OTP',
                'error' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
        }
}