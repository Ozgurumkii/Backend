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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get("distance/{postcode}", "App\Http\Controllers\MapController@distance");

Route::post("login", "App\Http\Controllers\AuthController@login");
Route::post("registervalidate", "App\Http\Controllers\AuthController@registervalidate");

Route::group(["middleware" => "auth.jwt"], function () {
    Route::get("logout", "App\Http\Controllers\AuthController@logout");
    Route::post("register", "App\Http\Controllers\AuthController@register");
    Route::resource("customers", "App\Http\Controllers\CustomerController");
    Route::resource("apartments", "App\Http\Controllers\ApartmentController");
    Route::resource("appointments", "App\Http\Controllers\AppointmentController");

    Route::get("apartmentlist", "App\Http\Controllers\ApartmentController@getall");
});
