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

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth','admin']], function () {
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
    | Routes for sales chart
    */
    /* Show dashboard page */
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    /* Show sales graph */
    Route::post('/dashboard/sales/graph', 'DashboardController@show')->name('dashboard.sales.graph');

    /*
    |--------------------------------------------------------------------------
    | Booking Statistics Routes
    |--------------------------------------------------------------------------
    |
    | Here we define booking statistics routes
    | Routes for booking status chart
    */
    /* Show booking statistics page */
    Route::get('/bookings/statistics', 'BookingStatisticsController@index')->name('booking.statistics');

    /* Show booking graph */
    Route::post('/bookings/statistics', 'BookingStatisticsController@show')->name('booking.statistics.graph');

    /*
    |--------------------------------------------------------------------------
    | User credit statistics Routes
    |--------------------------------------------------------------------------
    |
    | Here we define user credit statistics routes
    | Routes for user credit statistics chart
    */
    /* Show user credit statistics page */
    Route::get('/bookings/user/credit/statistics', 'UserCreditStatisticsController@index')->name('booking.user.credit.statistics');

    /* Show user credit graph */
    Route::post('/bookings/user/credit/statistics', 'UserCreditStatisticsController@show')->name('booking.user.credit.statistics.graph');

    /*
    |--------------------------------------------------------------------------
    | Routes for users
    |--------------------------------------------------------------------------
    |
    | Routes for listing, update, delete users, add club members, assign roles.
    */
    /* Listing users */
    Route::get('/users', 'UserlistController@index');

    /* Show datatable page */
    Route::post('/users/datatables', 'UserlistController@dataTables')->name('users.datatables');

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
    | Routes for listing, delete bookings, update payment, send vouchers
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
    | Routes for list, edit, add & delete cabins, update cabin price, update season date, update cabin type, assign user to cabin
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

    /*
    |--------------------------------------------------------------------------
    | Routes for mountain school bookings
    |--------------------------------------------------------------------------
    |
    | Routes for listing, delete
    */

    /* Listing bookings */
    Route::get('/mschool/bookings', 'MschoolBookingsController@index')->name('admin.mschool.bookings');

    /* Show datatable page */
    Route::post('/mschool/bookings', 'MschoolBookingsController@dataTables')->name('admin.mschool.bookings.datatables');

    /* Delete bookings */
    Route::delete('/mschool/bookings/{id}', 'MschoolBookingsController@destroy')->name('admin.mschool.bookings.delete');

    });
});

Route::prefix('cabinowner')->group(function () {
    Route::group(['middleware' => ['auth','cabinowner']], function () {
        /*
        |--------------------------------------------------------------------------
        | Dashboard Routes
        |--------------------------------------------------------------------------
        |
        | Here we define dashboard routes
        |
        */

        Route::get('/dashboard', 'Cabinowner\DashboardController@index')->name('cabinOwnerDash');

        /*
        |--------------------------------------------------------------------------
        | Routes for bookings
        |--------------------------------------------------------------------------
        |
        | Routes for listing bookings, send message, cancel booking
        */

        /* Listing bookings */
        Route::get('/bookings', 'Cabinowner\BookingController@index')->name('cabinowner.bookings');

        /* Show datatable page */
        Route::post('/bookings/datatables', 'Cabinowner\BookingController@dataTables')->name('cabinowner.bookings.datatables');

        /* Send message to guest */
        Route::post('/message/send', 'Cabinowner\BookingController@send');

        /* Cancel booking */
        Route::post('booking/cancel', 'Cabinowner\BookingController@cancelBooking');

        /*
        |--------------------------------------------------------------------------
        | Routes for mountain school bookings
        |--------------------------------------------------------------------------
        |
        | Routes for listing, send message
        */

        /* Listing bookings */
        Route::get('/mschool/bookings', 'Cabinowner\MountSchoolBookingsController@index');

        /* Show datatable page */
        Route::post('/mschool/bookings', 'Cabinowner\MountSchoolBookingsController@dataTables')->name('mschool.bookings.datatables');

        /* Send message to guest */
        Route::post('/mschool/message/send', 'Cabinowner\MountSchoolBookingsController@send');

    });
});