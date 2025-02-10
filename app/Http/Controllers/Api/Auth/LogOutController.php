<?php
namespace App\Http\Controllers\Api\Auth;

use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

Class LogOutController extends Controller{

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // User logout

public function logout(Request $request)
{
    try {

        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json([
                'error' => 'Token not provided'
            ], 400);
        }

         JWTAuth::invalidate($token);
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    } catch (TokenExpiredException $e) {
        return response()->json(['error' => 'Token already expired'], 401);
    } catch (TokenInvalidException $e) {
        return response()->json(['error' => 'Invalid token'], 401);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Failed to log out'], 500);
    }


}


}