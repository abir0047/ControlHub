<?php

use App\Http\Controllers\Auth\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//public route
Route::post("/register", [authController::class, 'register']);
Route::post("/login", [authController::class, 'login']);


//protected route
Route::group(['middleware'=> 'auth:sanctum'], function(){

    Route::post("/logout", [authController::class, 'logout']);
});
