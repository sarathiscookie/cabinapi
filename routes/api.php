<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API General Routes
|--------------------------------------------------------------------------
|
| Here we define general routes
|
*/

/* Listing countries */
Route::get('/countries', 'CountryController@index');

/* Listing clubs */
Route::get('/clubs', 'ClubController@index');


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
Route::get('/users', 'UserlistController@index'); //http://cabinapi.app/api/users?page=9

/* Get information from user */
Route::get('/users/{id}', 'UserlistController@show');

/* Update user status to activate and deactivate */
Route::put('/users/status/{statusId}/{id}', 'UserlistController@statusUpdate');

/* Update user status to activate and deactivate */
Route::put('/users/role/{roleId}/{id}', 'UserlistController@roleUpdate');

/* Update user details */
Route::put('/users/edit/{id}', 'UserlistController@update');

/* Add or Update club */
Route::put('/users/club/{id}', 'UserlistController@clubUpdate');

/* Delete user */
Route::delete('/users/{id}', 'UserlistController@destroy');

