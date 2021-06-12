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

Route::namespace('Api')->group( function () {

    Route::group([
        'prefix' => 'auth'

    ], function () {

        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');

    });
    
    Route::middleware([
        'jwt.verify',
        'jwt.auth'
        ])->group( function ($router) {
            
        Route::get('user', 'AuthController@user');
        
        Route::post('auth/logout', 'AuthController@logout');

        Route::post('refresh', 'AuthController@refresh');

        
        Route::namespace('Matrix')->group( function () {
            Route::post('2by2matrix', 'MatrixController@TwoByTwoMatrixProduct');
        });
    });

});


