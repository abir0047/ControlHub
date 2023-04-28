<?php

use App\Http\Controllers\Api\authController;
use App\Http\Controllers\Api\blogController;
use App\Http\Controllers\Api\examController;
use App\Http\Controllers\Api\orderController;
use App\Http\Controllers\Api\reportController;
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
Route::post("/signup", [authController::class, 'register']);
Route::post("/signin", [authController::class, 'login']);
Route::post("/googleSignIn", [authController::class, 'googleSignIn']);
Route::post("/logout", [authController::class, 'logout']);

Route::post("/readBlog", [blogController::class, 'readBlog']);
Route::post("/readSingleBlog", [blogController::class, 'readSingleBlog']);
Route::post("/passwordReset", [authController::class, 'passwordReset']);

//protected route
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("/showProfile", [authController::class, 'showProfile']);
    Route::post("/updatePassword", [authController::class, 'updatePassword']);
    Route::post("/updateInformation", [authController::class, 'updateInformation']);
    Route::post("/contactMail", [authController::class, 'contactMail']);


    Route::post("/getGroup", [examController::class, 'getGroup']);
    Route::post("/getQuestionSet", [examController::class, 'getQuestionSet']);
    Route::post("/getQuestion", [examController::class, 'getQuestion']);
    Route::post("/getSubjectBasedQuestion", [examController::class, 'getSubjectBasedQuestion']);

    Route::post("/inputReport", [reportController::class, 'inputReport']);
    Route::post("/getReport", [reportController::class, 'getReport']);

    Route::post("/postBlog", [blogController::class, 'postBlog']);
    Route::post("/updateBlog", [blogController::class, 'updateBlog']);
    Route::post("/deleteBlog", [blogController::class, 'deleteBlog']);

    Route::post("/makeOrder", [orderController::class, 'makeOrder']);
    Route::post("/getOrderOptions", [orderController::class, 'getOrderOptions']);
    Route::post("/getMyGroups", [orderController::class, 'getMyGroups']);
    Route::post("/couponPrice", [orderController::class, 'couponPrice']);
});
