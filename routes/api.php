<?php

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

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'],function(){
    Route::post('login',[\App\Http\Controllers\Api\User\AuthController::class,'login'])
        ->name('auth.login');
});

Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::post('user/search',[\App\Http\Controllers\Api\User\SearchController::class,'search'])
        ->name('user.search');

    Route::post('token/create',[\App\Http\Controllers\Api\User\TokenController::class,'create'])
        ->name('token.create');
    Route::delete('token/{token}',[\App\Http\Controllers\Api\User\TokenController::class,'destroy'])
        ->name('token.destroy');

    Route::post("logout",[\App\Http\Controllers\Api\User\AuthController::class,'logout']);
});

Route::group(['middleware' => 'auth:sanctum'],function() {

});

Route::get('/test',function(Request $request){
    return $request->user();
});
