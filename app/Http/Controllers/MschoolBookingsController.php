<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MountSchoolBooking;
use App\Userlist;
use App\Cabin;
use Auth;
use Mail;

class MschoolBookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.mSchoolBookings');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params        = $request->all();
        $columns       = array(
            1 => 'invoice_number',
            3 => 'usrLastname',
            4 => 'usrFirstname',
            5 => 'usrEmail',
            6 => 'check_in',
            7 => 'reserve_to',
            8 => 'beds',
            9 => 'dormitory',
            10 => 'sleeps',
            11 => 'status',
        );

        $totalData     = MountSchoolBooking::where('is_delete', 0)
            ->count();
        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $q             = MountSchoolBooking::where('is_delete', 0);

        if(!empty($request->input('search.value')))
        {
            $search   = $request->input('search.value');

            /* Checking email: Reason for using this method is because mongo $lookup is not working. Reason is user._id is objectid and booking.user is a string */
            $users     = Userlist::where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            })
                ->skip($start)
                ->take($limit)
                ->orderBy($order, $dir)
                ->get();

            if(count($users) > 0) {
                foreach ($users as $user) {
                    $q->where(function($query) use ($user) {
                        $query->where('user_id', $user->_id);
                    });

                    $totalFiltered = $q->where(function($query) use ($user) {
                        $query->where('user_id', $user->_id);
                    })
                        ->count();
                }
            }
            else {
                $q->where(function($query) use ($search) {
                    $query->where('invoice_number', 'like', "%{$search}%");
                });

                $totalFiltered = $q->where(function($query) use ($search) {
                    $query->where('invoice_number', 'like', "%{$search}%");
                })
                    ->count();
            }
        }

        /* Date range func begin */
        if($request->input('is_date_search') == 'yes')
        {
            //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
            $checkin_from           = explode("-", $request->input('daterange'));
            $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
            $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);

            $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                $query->whereBetween('check_in', [$dateBegin, $dateEnd])
                    ->where('cabin_name', $request->cabin);
            });

            $totalFiltered          =  $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                $query->whereBetween('check_in', [$dateBegin, $dateEnd])
                    ->where('cabin_name', $request->cabin);
            })
                ->count();
        }
        /* Date range func end */

        /* thead search functionality for booking number, email, status begin */
        if( !empty($params['columns'][1]['search']['value']))
        {
            $q->where(function($query) use ($params) {
                $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
            })
                ->count();
        }

        if( isset($params['columns'][11]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][11]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][11]['search']['value']}");
            })
                ->count();
        }

        if( !empty($params['columns'][5]['search']['value']) )
        {
            $users     = Userlist::where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][5]['search']['value']}%");
            })
                ->skip($start)
                ->take($limit)
                ->orderBy($order, $dir)
                ->get();

            if(count($users) > 0) {
                foreach ($users as $user) {
                    $q->where(function($query) use ($user) {
                        $query->where('user_id', $user->_id);
                    });

                    $totalFiltered = $q->where(function($query) use ($user) {
                        $query->where('user_id', $user->_id);
                    })
                        ->count();
                }
            }
        }
        /* tfoot search functionality for booking number, email, status end */

        $bookings      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        $data          = array();
        $noData        = '<span class="label label-default">'.__("cabinownerMountainSchoolBooking.noResult").'</span>';
        if(!empty($bookings)) {
            foreach ($bookings as $key => $booking) {
                $users = Userlist::where('_id', $booking->user_id)
                    ->get();
                foreach ($users as $user){
                    $usrEmail                              = $user->usrEmail;
                    $bookings[$key]['usrEmail']            = $usrEmail;
                    $bookings[$key]['usrFirstname']        = $user->usrFirstname;
                    $bookings[$key]['usrLastname']         = $user->usrLastname;
                    $bookings[$key]['usrCity']             = $user->usrCity;
                    $bookings[$key]['usrAddress']          = $user->usrAddress;
                    $bookings[$key]['usrTelephone']        = $user->usrTelephone;
                    $bookings[$key]['usrMobile']           = $user->usrMobile;
                    $bookings[$key]['usrZip']              = $user->usrZip;
                }
                /* Condition for booking status */
                if($booking->status == '1') {
                    $bookingStatusLabel = '<span class="label label-success">'.__("cabinownerMountainSchoolBooking.bookingFix").'</span>';
                }
                else if ($booking->status == '2') {
                    $bookingStatusLabel = '<span class="label label-danger">'.__("cabinownerMountainSchoolBooking.cancelled").'</span>';
                }
                else if ($booking->status == '3') {
                    $bookingStatusLabel = '<span class="label label-primary">'.__("cabinownerMountainSchoolBooking.completed").'</span>';
                }
                else if ($booking->status == '4') {
                    $bookingStatusLabel = '<span class="label label-info">'.__("cabinownerMountainSchoolBooking.request").'</span>';
                }
                else if ($booking->status == '5') {
                    $bookingStatusLabel = '<span class="label label-warning">'.__("cabinownerMountainSchoolBooking.bookingWaiting").'</span>';
                }
                else {
                    $bookingStatusLabel = $noData;
                }

                /* Checking check_in, reserve_to and booking date fields are available or not */
                if(!$booking->check_in){
                    $checkin_from = $noData;
                }
                else {
                    $checkin_from = ($booking->check_in)->format('d.m.y');
                }

                if(!$booking->reserve_to){
                    $reserve_to = $noData;
                }
                else {
                    $reserve_to = ($booking->reserve_to)->format('d.m.y');
                }

                if(!$booking->bookingdate){
                    $bookingdate = $noData;
                }
                else {
                    $bookingdate = ($booking->bookingdate)->format('d.m.y');
                }

                /* Condition to check user details null or not begin */
                if(empty($bookings[$key]['usrLastname'])) {
                    $last_name = $noData;
                }
                else {
                    $last_name = $bookings[$key]['usrLastname'];
                }

                if(empty($bookings[$key]['usrFirstname'])) {
                    $first_name = $noData;
                }
                else {
                    $first_name = $bookings[$key]['usrFirstname'];
                }

                if(empty($bookings[$key]['usrEmail'])) {
                    $user_email = $noData;
                }
                else {
                    $user_email = $bookings[$key]['usrEmail'];
                }

                if(empty($bookings[$key]['usrAddress'])) {
                    $usr_address = $noData;
                }
                else {
                    $usr_address = $bookings[$key]['usrAddress'];
                }

                if(empty($bookings[$key]['usrCity'])) {
                    $usr_city = $noData;
                }
                else {
                    $usr_city = $bookings[$key]['usrCity'];
                }

                if(empty($bookings[$key]['usrTelephone'])) {
                    $usr_telephone = $noData;
                }
                else {
                    $usr_telephone = $bookings[$key]['usrTelephone'];
                }

                if(empty($bookings[$key]['usrMobile'])) {
                    $usr_mobile = $noData;
                }
                else {
                    $usr_mobile = $bookings[$key]['usrMobile'];
                }

                if(empty($bookings[$key]['usrZip'])) {
                    $usr_zip = $noData;
                }
                else {
                    $usr_zip = $bookings[$key]['usrZip'];
                }

                /* Condition for beds, dorms and sleeps */
                if(empty($booking->beds) && empty($booking->dormitory))
                {
                    $sleeps    = $booking->sleeps;
                }
                else {
                    $beds      = $booking->beds;
                    $dormitory = $booking->dormitory;
                    $sleeps    = '-----';
                }
                if(empty($booking->beds)){
                    $beds      = '-----';
                }
                if(empty($booking->dormitory)){
                    $dormitory = '-----';
                }

                $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" /><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinownerMountainSchoolBooking.moreDetails").'</h4><div class="response"></div></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.address").'</h4><p class="list-group-item-text">'.$usr_address.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.city").'</h4><p class="list-group-item-text">'.$usr_city.'</p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.usrZip").'</h4><p class="list-group-item-text">'.$usr_zip.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.telephone").'</h4><p class="list-group-item-text">'.$usr_telephone.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinownerMountainSchoolBooking.mobile").'</h4><p class="list-group-item-text">'.$usr_mobile.'</p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("cabinownerMountainSchoolBooking.close").'</button></div></div></div></div>';
                $nestedData['invoice_number']          = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                $nestedData['usrLastname']             = $last_name;
                $nestedData['usrFirstname']            = $first_name;
                $nestedData['usrEmail']                = $user_email;
                $nestedData['check_in']                = $checkin_from;
                $nestedData['reserve_to']              = $reserve_to;
                $nestedData['beds']                    = $beds;
                $nestedData['dormitory']               = $dormitory;
                $nestedData['sleeps']                  = $sleeps;
                $nestedData['status']                  = $bookingStatusLabel;
                $data[]                                = $nestedData;
            }
            $json_data     = array(
                'draw'            => (int)$params['draw'],
                'recordsTotal'    => (int)$totalData,
                'recordsFiltered' => (int)$totalFiltered,
                'data'            => $data
            );

            return response()->json($json_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
