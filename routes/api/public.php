<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ContactUsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| public Routes
|--------------------------------------------------------------------------
|
| Here is where you can register public routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//هنا عملنا الاونلي علشان ميشغلش حاجه غير الاندكس والاوس سانكتم بتاكد ام التوكن سليمه
Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('doctors',DoctorController::class)->only(['index','show']);
    Route::apiResource('contactus',ContactUsController::class)->only('store');
});







