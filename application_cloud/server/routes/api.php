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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    // Matches The "/api/auth/login" URL
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');
    Route::get('signup/activate/{token}', 'Api\AuthController@signupActivate');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
        Route::post('upload', 'Api\AzureController@store');
        Route::get('list/blobs', 'Api\AzureController@getListBlob');
        Route::post('blob/analize/image', 'Api\AzureController@analizeImage');
        Route::post('blob/delete/image', 'Api\AzureController@deleteImage');
        Route::get('blob/share/image', 'Api\AzureController@shareImage');

        // generate shared key
        Route::get('blob/url', 'Api\AzureController@getSharedUrl');
    });
});

Route::group([    
    // 'namespace' => 'Api',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'Api\PasswordResetController@create');
    Route::get('find/{token}', 'Api\PasswordResetController@find');
    Route::post('reset', 'Api\PasswordResetController@reset');
});