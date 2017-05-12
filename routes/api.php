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

/* Get information from of user */
Route::get('/users/{id}', 'UserlistController@show')->name('users.show');

/* Trash user */
Route::put('/users/{id}', 'UserlistController@trash')->name('users.trash');