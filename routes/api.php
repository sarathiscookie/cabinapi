<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*
|--------------------------------------------------------------------------
| API Routes for users
|--------------------------------------------------------------------------
|
| Routes to listing, update, delete users
| Routes to add club members
| Routes to assign roles.
*/
/* Listing users */
Route::get('/users', 'UserlistController@index')->name('users.index'); //http://cabinapi.app/api/users?page=9

/* Get information from user */
Route::get('/users/{id}', 'UserlistController@show')->name('users.show');

/* Delete user */
Route::delete('/users/{id}', 'UserlistController@destroy')->name('users.destroy');

/* Update user status to activate and deactivate */
Route::put('/users/status/{statusId}/{id}', 'UserlistController@statusUpdate')->name('users.statusUpdate');