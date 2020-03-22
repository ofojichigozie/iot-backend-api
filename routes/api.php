<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//API routes for web application
Route::post('/remote_livestock_monitoring/user_login', 'UserController@authenticateUser');
Route::get('/remote_livestock_monitoring/livestock_data/{uuid}', 'LivestockDataController@showOne');
Route::get('/remote_livestock_monitoring/livestock_data', 'LivestockDataController@showAll');

//API routes for hardware system
Route::get('/remote_livestock_monitoring/livestock_data/{nfc_uuid}/{temperature}/{humidity}/{pulse_rate}/{loc_latitude}/{loc_longitude}', 'LivestockDataController@store');
