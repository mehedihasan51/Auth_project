<?php

// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/registation',[UserController::class,'UserRegitration']);
Route::post('/login',[UserController::class,'UserLogin']);
Route::post('/otp',[UserController::class,'SendOTPCode']); 
Route::post('/logout',[UserController::class,'logout'])->middleware('auth:api')->name('logout');