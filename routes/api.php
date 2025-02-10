<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\ResetPassword;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogOutController;
use App\Http\Controllers\Api\Auth\RegistrController;




Route::post('/registation',[RegistrController::class,'Regitration']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/otp',[OtpController::class,'SendOTPCode']); 
Route::post('/verify-otp',[OtpController::class,'verifyOtp']);
Route::post('/reset-password',[ResetPassword::class,'resetPassword']);
Route::post('/logout',[LogOutController::class,'logout'])->middleware(['auth.jwt']);


