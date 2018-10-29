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

/* Welcome page */
Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

/*
 |--------------------------------------------------------------------------
 | Override Login
 |--------------------------------------------------------------------------
 |
 | Here we define login and logout route
 |
*/

 /* Show login form */
 Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

 /* Sign in */
 Route::post('login', 'Auth\LoginController@login');

 /* Sign out */
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
    | User credit statistics Routes
    |--------------------------------------------------------------------------
    |
    | Here we define user credit statistics routes
    | Routes for user credit statistics chart
    */
    /* Show user credit statistics page */
    Route::get('/bookings/user/sales/statistics', 'SalesStatisticsController@index')->name('booking.user.sales.statistics');

    /* Show user credit graph */
    Route::post('/bookings/user/sales/statistics', 'SalesStatisticsController@show')->name('booking.user.sales.statistics.graph');

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
    Route::put('/users/status', 'UserlistController@statusUpdate');

    /* Update user status to activate and deactivate */
    Route::put('/users/role', 'UserlistController@roleUpdate');

    /* Delete money balance and store deleted money */
    Route::put('/users/balance/delete', 'UserlistController@balanceDelete');

    /* Update money balance */
    Route::put('/users/balance/update', 'UserlistController@balanceUpdate');

    /* Update user details */
    Route::put('/users/edit', 'UserlistController@update');

    /* Add or Update club */
    Route::put('/users/club/{id}', 'UserlistController@clubUpdate');

    /* Delete user */
    Route::delete('/users/destroy', 'UserlistController@destroy');


    /*
    |--------------------------------------------------------------------------
    | Routes for bookings
    |--------------------------------------------------------------------------
    |
    | Routes for listing, delete bookings, update payment, send vouchers
    */
    /* Listing bookings */
    Route::get('/bookings/{userId?}/{count?}', 'BookingController@index');

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

    /*
    |--------------------------------------------------------------------------
    | Routes for Cabins Lite View
    |--------------------------------------------------------------------------
    |
    | Routes for listing
    */
    /* Listing cabins */

    Route::get('/cabinlite', 'CabinLiteController@index')->name('admin.cabins.cabinlite');

    /* Show datatable page */
    Route::post('/cabinlite/datatables', 'CabinLiteController@dataTables')->name('admin.cabins.cabinlite.datatables');

    /* Create cabin */
    Route::get('/cabinlite/create', 'CabinLiteController@create')->name('admin.cabinlite.create');

    /* store cabin */
    Route::post('/cabinlite/store', 'CabinLiteController@store')->name('admin.cabinlite.store');

    /* update cabin */
    Route::post('/cabinlite/update', 'CabinLiteController@update')->name('admin.cabinlite.update');

    /* Update contact details */
    Route::post('/cabinlite/updatecontactinfo', 'CabinLiteController@updateContactinfo')->name('admin.cabinlite.updatecontactinfo');

    /* Update Billing details */
    Route::post('/cabinlite/updatebillinginfo', 'CabinLiteController@updateBillingInfo')->name('admin.cabinlite.updatebillinginfo');

    /* Update Cabin details */
    Route::post('/cabinlite/updatecabininfo', 'CabinLiteController@updateCabinInfo')->name('admin.cabinlite.updatecabininfo');

    /* get cabin Owners */
    Route::get('/cabinlite/getcabinowners', 'CabinLiteController@getCabinOwners') ;

    /* get Country */
    Route::get('/cabinlite/getcountry', 'CabinLiteController@getCountry') ;

    /* editcabin view*/
    Route::get('/cabinlite/edit/{id}', 'CabinLiteController@edit');

    /* edit contingent cabin view */
    Route::get('/cabinlite/contingent/{id}', 'ContingentController@edit');

    /* update contingent  */
     Route::post('/cabinlite/contingent/update', 'ContingentController@update')->name('admin.cabinlite.updatecontingent');

     /* edit Season Details   */
     Route::get('/cabinlite/seasondetails/{id}', 'CabinLiteOpenCloseSeasonController@index');
     Route::post('/cabinlite/seasondetails/store', 'CabinLiteOpenCloseSeasonController@store')->name('admin.cabinlite.season.store');
     Route::post('/cabinlite/seasondetails/lists', 'CabinLiteOpenCloseSeasonController@lists');
     Route::get('/cabinlite/seasondetails/summer/edit', 'CabinLiteOpenCloseSeasonController@editSummer');

     Route::get('/cabinlite/seasondetails/winter/edit', 'CabinLiteOpenCloseSeasonController@editWinter');

     /* update summer Season Details   */
     Route::post('/cabinlite/seasondetails/summer/update', 'CabinLiteOpenCloseSeasonController@updateSummer')->name('admin.cabinlite.season.summer.update');

     /* update winter Season Details   */
     Route::post('/cabinlite/seasondetails/winter/update', 'CabinLiteOpenCloseSeasonController@updateWinter')->name('admin.cabinlite.season.winter.update');

     /* delete summer Season    */
     Route::post('/cabinlite/seasondetails/summer/delete', 'CabinLiteOpenCloseSeasonController@deleteSummer')->name('admin.cabinlite.season.summer.delete');

     /* delete winter Season    */
     Route::post('/cabinlite/seasondetails/winter/delete', 'CabinLiteOpenCloseSeasonController@deleteWinter')->name('admin.cabinlite.season.winter.delete');

     /*
      |--------------------------------------------------------------------------
      | Routes for Image Upload
      |--------------------------------------------------------------------------
      |
      | Routes for upload,listing, delete,edit
     */
     /* list Image */
     Route::get('/cabinlite/image/{id}', 'CabinLiteImageController@index')->name('cabin.image');

     /* list create */
     Route::get('/cabinlite/image/{id}/create', 'CabinLiteImageController@create')->name('cabin.image.create');

     /* Store Section*/
     Route::post('/cabinlite/image/{id}/store', 'CabinLiteImageController@store')->name('cabin.image.store');

     /* Delete an image */
     Route::post('/cabinlite/image/{id}/delete', 'CabinLiteImageController@deleteImage')->name('cabin.image.delete');

     /* Set Main Image */
     Route::post('/cabinlite/image/{id}/setMainImg', 'CabinLiteImageController@setMainImg')->name('cabin.image.setMainImg');

     /* Set Profile Image*/
     Route::post('/cabinlite/image/{id}/setProfileImg', 'CabinLiteImageController@setProfileImg')->name('cabin.image.setProfileImg');

     /* Temporary purpose */
     /* To list deleted cart via api */
     Route::get('/deletedCartViaApi', function () {
         return view('backend.cartDeletedTemporary');
     });

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
        | Routes for listing bookings, send message, cancel booking, create booking
        */
        /* Listing bookings */
        Route::get('/bookings/{bookId?}', 'Cabinowner\BookingController@index')->name('cabinowner.bookings');

        /* Show datatable page */
        Route::post('/bookings/datatables', 'Cabinowner\BookingController@dataTables')->name('cabinowner.bookings.datatables');

        /* Send message to guest */
        Route::post('/bookings/message/send', 'Cabinowner\BookingController@send');

        /* Cancel booking */
        Route::post('booking/cancel', 'Cabinowner\BookingController@cancelBooking');

        /*
        |--------------------------------------------------------------------------
        | Routes for create booking, check booking availability
        |--------------------------------------------------------------------------
        |
        | Routes for view available dates, store and check availability
        */
        /* Create bookings */
        Route::get('/create/booking', 'Cabinowner\CreateBookingController@index')->name('cabinowner.create.booking');

        /* Store booking */
        Route::post('/store/booking', 'Cabinowner\CreateBookingController@store')->name('cabinowner.store.booking');

        /* Show available dates in calendar */
        Route::post('/check/availability/calendar', 'Cabinowner\CreateBookingController@calendarAvailability')->name('cabinowner.check.availability.calendar');

        /* Check booking availability */
        Route::post('/check/availability', 'Cabinowner\CreateBookingController@checkAvailability')->name('cabinowner.check.availability');

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

        /*
        |--------------------------------------------------------------------------
        | Routes for mountain school inquiry
        |--------------------------------------------------------------------------
        |
        | Routes for listing, delete, send message and approve or reject mountain school inquiry
        */
        /* Listing mountain school inquiry */
        Route::get('/inquiry/mschool/{bookId?}/{senderId?}', 'Cabinowner\MountSchoolInquiryBookingController@index')->name('cabinowner.inquiry.mschool');

        /* Show datatable page */
        Route::post('/inquiry/mschool', 'Cabinowner\MountSchoolInquiryBookingController@dataTables')->name('cabinowner.inquiry.mschool.datatables');

        /* Update inquiry status approve */
        Route::put('/inquiry/mschool/approve', 'Cabinowner\InquiryBookingsController@approveStatus')->name('cabinowner.inquiry.mschool.status.approve');

        /* Update inquiry status reject */
        Route::put('/inquiry/mschool/reject', 'Cabinowner\InquiryBookingsController@rejectStatus')->name('cabinowner.inquiry.mschool.status.reject');

        /* Reply message */
        Route::post('/inquiry/mschool/message/send', 'Cabinowner\InquiryBookingsController@sendMessage')->name('cabinowner.inquiry.mschool.message.send');

        /*
        |--------------------------------------------------------------------------
        | Routes for normal inquiry
        |--------------------------------------------------------------------------
        |
        | Routes for listing, delete, send message and approve or reject normal inquiry
        */
        /* Listing inquiry */
        Route::get('/inquiry/{bookId?}/{senderId?}', 'Cabinowner\InquiryBookingsController@index')->name('cabinowner.inquiry');

        /* Show datatable page */
        Route::post('/inquiry', 'Cabinowner\InquiryBookingsController@dataTables')->name('cabinowner.inquiry.datatables');

        /* Update inquiry status approve */
        Route::put('/inquiry/approve', 'Cabinowner\InquiryBookingsController@approveStatus')->name('cabinowner.inquiry.status.approve');

        /* Update inquiry status reject */
        Route::put('/inquiry/reject', 'Cabinowner\InquiryBookingsController@rejectStatus')->name('cabinowner.inquiry.status.reject');

        /* Reply message */
        Route::post('/message/send', 'Cabinowner\InquiryBookingsController@sendMessage')->name('cabinowner.inquiry.message.send');

        /*
        |--------------------------------------------------------------------------
        | Routes for contingent
        |--------------------------------------------------------------------------
        |
        | Routes for edit contingent
        */
        /* Edit contingent */
        Route::get('/contingent', 'Cabinowner\ContingentController@index')->name('cabinowner.contingent');

        Route::post('/contingent/update', 'Cabinowner\ContingentController@update')->name('cabinowner.contingent.update');

        /*
        |--------------------------------------------------------------------------
        | Routes for open close season
        |--------------------------------------------------------------------------
        |
        | Routes for view, store, edit, delete seasons
        */
        /* Edit season */
        Route::get('/season', 'Cabinowner\OpeningClosingSeasonController@index')->name('cabinowner.season');

        /* Create season */
        Route::get('/season/create', 'Cabinowner\OpeningClosingSeasonController@create')->name('cabinowner.season.create');

        /* Store season */
        Route::post('/season/store', 'Cabinowner\OpeningClosingSeasonController@store')->name('cabinowner.season.store');

        /* Delete summer season */
        Route::post('/season/summer/delete', 'Cabinowner\OpeningClosingSeasonController@deleteSummer')->name('cabinowner.summer.season.delete');

        /* Summer season edit */
        Route::get('/season/summer/edit/{id}', 'Cabinowner\OpeningClosingSeasonController@editSummer')->name('cabinowner.summer.season.edit');

        /* Update summer season */
        Route::post('/season/summer/update', 'Cabinowner\OpeningClosingSeasonController@updateSummer')->name('cabinowner.summer.season.update');

        /* Winter season edit*/
        Route::get('/season/winter/edit/{id}', 'Cabinowner\OpeningClosingSeasonController@editWinter')->name('cabinowner.winter.season.edit');

        /* Delete winter season*/
        Route::post('/season/winter/delete', 'Cabinowner\OpeningClosingSeasonController@deleteWinter')->name('cabinowner.winter.season.delete');

        /* Update winter season */
        Route::post('/season/winter/update', 'Cabinowner\OpeningClosingSeasonController@updateWinter')->name('cabinowner.winter.season.update');

        /*
        |--------------------------------------------------------------------------
        | Routes for cabin, contact and bill information
        |--------------------------------------------------------------------------
        |
        | Routes for view, store, edit, delete informations
        */
        /* List details */
        Route::get('/details', 'Cabinowner\DetailsController@index')->name('cabinowner.details');

        /* Edit contact details */
        Route::get('/details/contact', 'Cabinowner\DetailsController@editContactInfo')->name('cabinowner.details.contact');

        /* Update contact details */
        Route::post('/details/contact/update', 'Cabinowner\DetailsController@updateContactInfo')->name('cabinowner.details.contact.update');

        /* Edit billing details */
        Route::get('/details/billing', 'Cabinowner\DetailsController@editBillingInfo')->name('cabinowner.details.billing');

        /* Update billing details */
        Route::post('/details/billing/update', 'Cabinowner\DetailsController@updateBillingInfo')->name('cabinowner.details.billing.update');

        /* Edit cabin details */
        Route::get('/details/cabin', 'Cabinowner\DetailsController@editCabinIfo')->name('cabinowner.details.cabin');

        /* Update billing details */
        Route::post('/details/cabin/update', 'Cabinowner\DetailsController@updateCabinIfo')->name('cabinowner.details.cabin.update');

        /*
        |--------------------------------------------------------------------------
        | Routes for Image Upload
        |--------------------------------------------------------------------------
        |
        | Routes for upload,listing, delete,edit
        */
        /* list Image */
        Route::get('/image', 'Cabinowner\ImageController@index')->name('cabinowner.image');

        /* list create */
        Route::get('/image/create', 'Cabinowner\ImageController@create')->name('cabinowner.image.create');

        /* Store Section*/
        Route::post('/image/store', 'Cabinowner\ImageController@store')->name('cabinowner.image.store');

        /* Delete an image */
        Route::post('/image/delete', 'Cabinowner\ImageController@deleteImage')->name('cabinowner.image.delete');

        /* Set Main Image */
        Route::post('/image/setMainImg', 'Cabinowner\ImageController@setMainImg')->name('cabinowner.image.setMainImg');

        /* Set Profile Image*/
        Route::post('/image/setProfileImg', 'Cabinowner\ImageController@setProfileImg')->name('cabinowner.image.setProfileImg');

        /*
        |--------------------------------------------------------------------------
        | Routes for MountainSchool Users Listing
        |--------------------------------------------------------------------------
        |
        | Routes for Mountain School Users
        */
        /* list Image */
        Route::get('/msusers', 'Cabinowner\MountSchoolUsersController@index')->name('cabinowner.msusers');

        /* Show datatable page */
        Route::post('/msusers/datatables', 'Cabinowner\MountSchoolUsersController@dataTables')->name('msusers.datatables');

        /*
        |--------------------------------------------------------------------------
        | Routes for Pricelist
        |--------------------------------------------------------------------------
        |
        | Routes for listing, add ,edit
        */
        /* list Prices */
        Route::get('/pricelist', 'Cabinowner\PriceListController@index')->name('cabinowner.pricelist');

        /* list create */
        Route::get('/pricelist/create', 'Cabinowner\PriceListController@create')->name('cabinowner.pricelist.create');

        /*save image to storage*/
        Route::post('/pricelist/store', 'Cabinowner\PriceListController@store')->name('cabinowner.pricelist.store');

        /*
        |--------------------------------------------------------------------------
        | Routes for Statistics
        |--------------------------------------------------------------------------
        |
        | Statistics to show guests count
        */
        /* View statistics chart */
        Route::get('/statistics/guests', 'Cabinowner\StatisticsGuestsController@index')->name('cabinowner.statistics.guests');

        /* Listing count of guests statistics */
        Route::post('/statistics/guests/count', 'Cabinowner\StatisticsGuestsController@store')->name('cabinowner.statistics.guests.count');

    });
 });

 Route::prefix('mountainschool')->group(function () {
    Route::group(['middleware' => ['auth','mountainschool']], function () {
        /*
        |--------------------------------------------------------------------------
        | Dashboard Routes
        |--------------------------------------------------------------------------
        |
        | Here we define dashboard routes
        |
        */
        Route::get('/dashboard', 'Mountainschool\DashboardController@index')->name('mountainschoolDash');

        /* Listing bookings */
        Route::get('/bookings', 'Mountainschool\BookingController@index')->name('mountainschool.bookings');

        /* Show datatable page */
        Route::post('/bookings/datatables', 'Mountainschool\BookingController@dataTables')->name('mountainschool.bookings.datatables');

        /* new booking */
        Route::get('/bookings/create', 'Mountainschool\TourController@createTourNewBooking');

        /* get tours for  booking */
        Route::get('/tours/gettour/{id}', 'Mountainschool\TourController@getTourForBooking');

        /* Listing tours */
        Route::get('/tours', 'Mountainschool\TourController@index')->name('mountainschool.tours');

        Route::post('/tours/datatables', 'Mountainschool\TourController@dataTables')->name('mountainschool.tours.datatables');

        /* Add new cabin */
        Route::post('/tours/createtour/createnewcabin', 'Mountainschool\TourController@createNewCabin')->name('mountainschool.tours.createnewcabin');

        /* Get cabins */
        Route::get('/tours/addnewcabin', 'Mountainschool\TourController@addNewCabin')->name('mountainschool.tours.addnewcabin');

        /* Create tours */
        Route::get('/tours/createtour', 'Mountainschool\TourController@createTour')->name('mountainschool.createtour');

        /* Edit tours */
        Route::get('/tours/edittour/{id}', 'Mountainschool\TourController@editTour')->name('mountainschool.edittour');

        /* Update tours * */
        Route::post('/tours/updatetour', 'Mountainschool\TourController@updateTour')->name('mountainschool.updatetour');

        /* Store tours */
        Route::post('/tours/store', 'Mountainschool\TourController@store')->name('mountainschool.tours.store');

        /* mydata user deatils edit */
        Route::get('/mydata', 'Mountainschool\TourController@EditMyData')->name('mountainschool.mydata');

        /* mydata user deatils update */
        Route::post('/updatemydata', 'Mountainschool\TourController@updateMyData')->name('mountainschool.mydata.update');

        /* get update password view */
        Route::get('/editpassword', 'Mountainschool\TourController@editPassword');

        /* mydata user deatils update */
        Route::post('/updatepassword', 'Mountainschool\TourController@updatePassword')->name('mountainschool.mydata.updatepassword');

        /* Calendar checkAvailability */
        /*Route::post('/calendarAvailability', 'Mountainschool\TourController@calendarAvailability')->name('mountainschool.calendarAvailability');*/

        /* Get dates when page loads */
        Route::post('/calendar/ajax', 'Mountainschool\CalendarController@calendarAvailability')->name('calendar');

        /* checkAvailability */
        Route::post('/checkAvailability', 'Mountainschool\TourController@checkAvailability')->name('mountainschool.checkAvailability');

        /*mydata user deatils update */
        Route::post('/bookingStore', 'Mountainschool\TourController@bookingStore')->name('mountainschool.tours.bookingStore');

        /*duplicatingBooking Tour */
        // Route::get('/duplicatingBooking', 'Mountainschool\TourController@duplicatingBooking') ;
        /* Basic settings Tour */

        Route::get('/basicsettings', 'Mountainschool\TourController@basicSettings');

        /* Settings update */
        Route::post('/updateBasicSettings', 'Mountainschool\TourController@updateBasicSettings')->name('mountainschool.updatebasicsettings');
    });
 });