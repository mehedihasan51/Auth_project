<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\ResetPassword;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegistrController;




Route::post('/registation',[RegistrController::class,'Regitration']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/otp',[OtpController::class,'SendOTPCode']); 
Route::post('/send-otp',[OtpController::class,'otpSend']);
Route::post('/verify-otp',[OtpController::class,'verifyOtp']);
Route::post('/reset-password',[ResetPassword::class,'resetPassword']);
Route::post('/logout',[LogoutController::class,'logout'])->middleware('auth:api')->name('logout');

// Route::group(['middleware' => 'api','namespace' => 'App\Http\Controllers','prefix' => 'auth'], function ($router) {

//     Route::post('login', [LoginController::class,'UserLogin']);

// });