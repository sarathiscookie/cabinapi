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

/* Listing lands */
Route::get('/lands', 'LandController@index');

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*
|--------------------------------------------------------------------------
| API Routes for users
|--------------------------------------------------------------------------
|
| Routes for listing, update, delete users
| Routes for add club members
| Routes for assign roles.
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


/*
|--------------------------------------------------------------------------
| API Routes for bookings
|--------------------------------------------------------------------------
|
| Routes for listing & delete bookings
| Routes for update payment
| Routes for send vouchers.
*/
/* Listing bookings */
Route::get('/bookings', 'BookingController@index'); //http://cabinapi.app/api/bookings?page=9

/* Get information from user */
Route::get('/bookings/{id}', 'BookingController@show');

/* Update payment status */
Route::put('/bookings/payment/{status}/{id}', 'BookingController@update');

/* Send invoice*/
Route::post('/bookings/voucher/{id}', 'BookingController@sendInvoice');

/* Delete bookings */
Route::delete('/bookings/{id}', 'BookingController@destroy');

/*
|--------------------------------------------------------------------------
| API Routes for roles
|--------------------------------------------------------------------------
|
| Routes for list, edit, add & delete role
*/
/* Listing roles */
Route::get('/roles', 'RoleController@index'); //http://cabinapi.app/api/roles?page=9

/* Create role */
Route::post('/roles', 'RoleController@store');

/* Update roles */
Route::put('/roles/{id}', 'RoleController@update');

/* Deleted roles */
Route::delete('/roles/{id}', 'RoleController@destroy');

/*
|--------------------------------------------------------------------------
| API Routes for regions
|--------------------------------------------------------------------------
|
| Routes for list, edit, add & delete regions
*/
/* Listing regions */
Route::get('/regions', 'RegionController@index'); //http://cabinapi.app/api/regions?page=9

/* Create regions */
Route::post('/regions', 'RegionController@store');

/* Update regions */
Route::put('/regions/{id}', 'RegionController@update');

/* Deleted regions */
Route::delete('/regions/{id}', 'RegionController@destroy');


/*
|--------------------------------------------------------------------------
| API Routes for fetch booking within date range and send vouchers
|--------------------------------------------------------------------------
|
| Routes for completed booking within date range & send vouchers
*/
/* Listing vouchers */
Route::get('/daterange/bookings/{beginDate?}/{endDate?}', 'InvoiceController@index'); //http://cabinapi.app/api/bookings/completed?page=9

/* Send bulk invoice */
Route::post('/daterange/bookings/invoices/send', 'InvoiceController@sendInvoice');


/*
|--------------------------------------------------------------------------
| API Routes for cabins
|--------------------------------------------------------------------------
|
| Routes for list, edit, add & delete cabins
| Routes for update cabin price
| Routes for update season date
| Routes for update cabin type
| Routes for assign user to cabin
*/
/* Listing cabins */
Route::get('/cabins', 'CabinController@index'); //http://cabinapi.app/api/cabins?page=9

/* Listing other cabins */
Route::get('/cabins/othercabins', 'CabinController@indexOtherCabin'); //http://cabinapi.app/api/cabins/othercabins?page=9

/* Get information of cabin */
Route::get('/cabins/{id}', 'CabinController@show');

/* Assign user to cabin */
Route::put('/cabins/assign/{userId}/{id}', 'CabinController@assignUser');

/* Update cabin type */
Route::put('/cabins/type/{type}/{id}', 'CabinController@updateType');

/* Delete cabin */
Route::delete('/cabins/{id}', 'CabinController@destroy');