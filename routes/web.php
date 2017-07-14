<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

/*Route::get('/home', 'HomeController@index')->name('home');*/

//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function() {});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


// Password Reset Routes...
/*Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');*/

Route::prefix('admin')->middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | General Routes
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

    /* Listing facilities */
    Route::get('/facilities', 'FacilityController@index');

    /*
    |--------------------------------------------------------------------------
    | Dashboard Routes
    |--------------------------------------------------------------------------
    |
    | Here we define dashboard routes
    |
    */

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Routes for users
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
    | Routes for bookings
    |--------------------------------------------------------------------------
    |
    | Routes for listing & delete bookings
    | Routes for update payment
    | Routes for send vouchers.
    */

    /* Listing bookings */
    Route::get('/bookings', 'BookingController@index');

    /* Show datatable page */
    Route::post('/bookings/datatables', 'BookingController@dataTables')->name('bookings.datatables');

    /* Get individual information from bookings */
    Route::get('/bookings/{id}', 'BookingController@show');

    /* Update payment status */
    Route::put('/bookings/payment/status', 'BookingController@update')->name('bookings.payment.status.update');

    /* Update each booking payment status */
    Route::put('/bookings/payment/status/individual', 'BookingController@updateIndividual');

    /* Send invoice*/
    Route::post('/bookings/voucher/{id}', 'BookingController@sendInvoice');

    /* Delete bookings */
    Route::delete('/bookings/{id}', 'BookingController@destroy')->name('bookings.delete');

    /*
    |--------------------------------------------------------------------------
    | Routes for roles
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
    | Routes for regions
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
    | Routes for fetch booking within date range and send vouchers
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
    | Routes for cabins
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

    /* Get cabin price */
    Route::get('/cabins/price/{id}', 'CabinController@showPrice');

    /* Update cabin price */
    Route::put('/cabins/price/{id}', 'CabinController@updatePrice');

    /* Assign user to cabin */
    Route::put('/cabins/assign/{userId}/{id}', 'CabinController@assignUser');

    /* Update cabin type */
    Route::put('/cabins/type/{type}/{id}', 'CabinController@updateType');

    /* Delete cabin */
    Route::delete('/cabins/{id}', 'CabinController@destroy');


});

Route::prefix('cabinowner')/*->middleware('auth')*/->group(function () {
   /*
   |--------------------------------------------------------------------------
   | Dashboard Routes
   |--------------------------------------------------------------------------
   |
   | Here we define dashboard routes
   |
   */

    Route::get('/index', 'Cabinowner\IndexController@index')->name('cabinOwnerIndex');

    /*
    |--------------------------------------------------------------------------
    | Routes for bookings
    |--------------------------------------------------------------------------
    |
    | Routes for listing bookings
    */

    /* Listing bookings */
    Route::get('/bookings', 'Cabinowner\BookingController@index')->name('cabinowner.bookings');

    /* Show datatable page */
    Route::post('/bookings/datatables', 'Cabinowner\BookingController@dataTables')->name('cabinowner.bookings.datatables');
});

/* Statistics purpose */
/*Route::get('/cabins/name/{bookingCabinName}', 'CabinController@statistics');*/