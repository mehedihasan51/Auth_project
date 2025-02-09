<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;

Class LogoutController extends Controller{

    /**
     * @view JsonResponse
     */

    public function logout()
    {

        if (!auth('api')->check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        auth('api')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

}