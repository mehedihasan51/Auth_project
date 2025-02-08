<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\UserController;

use App\Http\Controllers\Api\Auth\UserController;

Route::get('/', function () {
    return view('welcome');
});



Route::post('/user-registation',[UserController::class,'UserRegitration']);
Route::post('/user-login',[UserController::class,'UserLogin']);
Route::post('/send-otp',[UserController::class,'SendOTPCode']);