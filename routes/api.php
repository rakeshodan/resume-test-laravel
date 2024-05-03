<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post("register", [UserController::class, "register"]);
        Route::post("login", [AuthController::class, "login"]);

        Route::group(['middleware' => ['auth:api']], function () {
            Route::get("/", [UserController::class, "getAuthUser"]);                
        });
    });

    Route::group(['prefix' => 'resume'], function () {

        Route::group(['middleware' => ['auth:api']], function () {                
            Route::post('store',[ResumeController::class, "store"]);
            Route::post('update/{id}',[ResumeController::class, "update"]);
            Route::post('group/update/{id}',[ResumeGroupMapperController::class, "update"]);
        });

        Route::get('/{username}',[ResumeController::class, "get"]);
    });
});