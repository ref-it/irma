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
Route::middleware('auth:api')->group(function (){
    Route::any('user', \App\Http\Controllers\Api\SocialiteUser::class);

    Route::middleware('scope:committees')->group(function (){
        Route::any('my/committees', [\App\Http\Controllers\Api\Committees::class, 'all']);
        Route::any('my/committees/{community_uid}', [\App\Http\Controllers\Api\Committees::class, 'fromCommunity']);
    });

    Route::middleware('scope:groups')->group(function (){
        Route::any('my/groups', [\App\Http\Controllers\Api\Groups::class, 'all']);
        Route::any('my/groups/{community_uid}', [\App\Http\Controllers\Api\Groups::class, 'fromCommunity']);
    });

});


