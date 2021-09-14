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

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('file', 'FileController');
    Route::apiResource('geotype', 'GeotypeController');
    Route::apiResource('project', 'ProjectController');
    Route::apiResource('shape', 'ShapeController');
});

Route::post('login', 'AuthController@login');



