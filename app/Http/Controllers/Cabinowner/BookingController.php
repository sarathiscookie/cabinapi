<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Userlist;
use App\Tempuser;
use App\Cabin;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cabinowner.bookings');
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
            2 => 'usrLastname',
            3 => 'usrFirstname',
            4 => 'usrEmail',
            5 => 'checkin_from',
            6 => 'reserve_to',
            7 => 'sleeps',
            8 => 'status',
            9 => 'prepayment_amount',
            10 => 'answered',
            11 => 'actions'
        );

        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', '586b88b4d2ae676a129b0421') // Replace hard-code id with auth id
            ->get();
        if(count($cabins) > 0) {
            foreach ($cabins as $cabin)
            {
                $cabin_name = $cabin->name;
                $totalData  = Booking::where('is_delete', 0)
                    ->where('cabinname', $cabin_name)
                    ->count();

                $totalFiltered = $totalData;
                $limit         = (int)$request->input('length');
                $start         = (int)$request->input('start');
                $order         = $columns[$params['order'][0]['column']]; //contains column index
                $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

                if(empty($request->input('search.value')))
                {
                    $bookings      = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->skip($start)
                        ->take($limit)
                        ->orderBy($order, $dir)
                        ->get();
                }
                else {
                    $search   = $request->input('search.value');

                    /* Checking email: Reason for using this method is because mongo $lookup is not working. Reason is user._id is objectid and booking.user is a string */
                    $users     = Userlist::select('_id', 'usrEmail', 'usrFirstname', 'usrLastname')
                        ->where('is_delete', 0)
                        ->where(function($query) use ($search) {
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
                            $bookings = Booking::where('is_delete', 0)
                                ->where('cabinname', $cabin_name)
                                ->where(function($query) use ($user) {
                                    $query->where('user', $user->_id);
                                })
                                ->skip($start)
                                ->take($limit)
                                ->orderBy($order, $dir)
                                ->get();

                            $totalFiltered = Booking::where('is_delete', 0)
                                ->where('cabinname', $cabin_name)
                                ->where(function($query) use ($user) {
                                    $query->where('user', $user->_id);
                                })
                                ->count();
                        }
                    }
                    else {
                        $bookings = Booking::where('is_delete', 0)
                            ->where('cabinname', $cabin_name)
                            ->where(function($query) use ($search) {
                                $query->where('invoice_number', 'like', "%{$search}%");
                            })
                            ->skip($start)
                            ->take($limit)
                            ->orderBy($order, $dir)
                            ->get();

                        $totalFiltered = Booking::where('is_delete', 0)
                            ->where('cabinname', $cabin_name)
                            ->where(function($query) use ($search) {
                                $query->where('invoice_number', 'like', "%{$search}%");
                            })
                            ->count();
                    }
                }

                /* thead search functionality for booking number, lastname, firstname, email, fromDate begin */
                if( !empty($params['columns'][1]['search']['value'])
                    || isset($params['columns'][8]['search']['value']) )
                {
                    $bookings = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where(function($query) use ($params) {
                            $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%")
                                ->orWhere('status', "{$params['columns'][8]['search']['value']}");
                        })
                        ->skip($start)
                        ->take($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalFiltered = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where(function($query) use ($params) {
                            $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%")
                                ->orWhere('status', "{$params['columns'][8]['search']['value']}");
                        })
                        ->count();
                }

                if( !empty($params['columns'][2]['search']['value'])
                    || !empty($params['columns'][3]['search']['value'])
                    || !empty($params['columns'][4]['search']['value']) )
                {
                    $users     = Userlist::select('_id', 'usrFirstname', 'usrEmail', 'usrLastname')
                        ->where('is_delete', 0)
                        ->where(function($query) use ($params) {
                            $query->where('usrLastname', 'like', "%{$params['columns'][2]['search']['value']}%")
                                ->orWhere('usrFirstname', "{$params['columns'][3]['search']['value']}")
                                ->orWhere('usrEmail', "{$params['columns'][4]['search']['value']}");
                        })
                        ->skip($start)
                        ->take($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    if(count($users) > 0) {
                        foreach ($users as $user) {
                            $bookings = Booking::where('is_delete', 0)
                                ->where('cabinname', $cabin_name)
                                ->where(function($query) use ($user) {
                                    $query->where('user', $user->_id);
                                })
                                ->skip($start)
                                ->take($limit)
                                ->orderBy($order, $dir)
                                ->get();

                            $totalFiltered = Booking::where('is_delete', 0)
                                ->where('cabinname', $cabin_name)
                                ->where(function($query) use ($user) {
                                    $query->where('user', $user->_id);
                                })
                                ->count();
                        }
                    }
                }

                if( !empty($params['columns'][5]['search']['value']) ) {

                    $checkin_from    = explode("-", $params['columns'][5]['search']['value']);
                    $dateBegin       = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
                    $dateEnd         = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);
                    $bookings        = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where(function($query) use ($params, $dateBegin, $dateEnd) {
                            $query->whereBetween('checkin_from', array($dateBegin, $dateEnd));
                        })
                        ->skip($start)
                        ->take($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalFiltered = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where(function($query) use ($params, $dateBegin, $dateEnd) {
                            $query->whereBetween('checkin_from', array($dateBegin, $dateEnd));
                        })
                        ->count();

                }
                /* thead search functionality for booking number, lastname, firstname, email end */


                $data          = array();
                $noData        = '<span class="label label-default">'.__("admin.noResult").'</span>';
                if(!empty($bookings)) {
                    foreach ($bookings as $key => $booking)
                    {
                        /* Condition for checking who booked bookings. If a booking collection has temp_user_id then show notification (Booked by cabin owner) otherwise user email. begin*/
                        if($booking->temp_user_id != ""){
                            $tempUsers = Tempuser::where('_id', $booking->temp_user_id)
                                ->get();
                            foreach ($tempUsers as $tempUser){
                                $usrEmail                       = $tempUser->usrEmail;
                                $bookings[$key]['bookedBy']     = 'cabinowner';
                                $bookings[$key]['usrEmail']     = $usrEmail;
                                $bookings[$key]['usrFirstname'] = $tempUser->usrFirstname;
                                $bookings[$key]['usrLastname']  = $tempUser->usrLastname;
                                $bookings[$key]['usrAddress']   = $tempUser->usrAddress;
                                $bookings[$key]['usrTelephone'] = $tempUser->usrTelephone;
                                $bookings[$key]['usrMobile']    = $tempUser->usrMobile;
                            }
                        }
                        else{
                            $users = Userlist::where('_id', $booking->user)
                                ->get();
                            foreach ($users as $user){
                                $usrEmail                       = $user->usrEmail;
                                $bookings[$key]['usrEmail']     = $usrEmail;
                                $bookings[$key]['usrFirstname'] = $user->usrFirstname;
                                $bookings[$key]['usrLastname']  = $user->usrLastname;
                                $bookings[$key]['usrAddress']   = $user->usrAddress;
                                $bookings[$key]['usrTelephone'] = $user->usrTelephone;
                                $bookings[$key]['usrMobile']    = $user->usrMobile;
                            }
                        }

                        /* Condition for booking status begin */
                        if($booking->status == '1') {
                            $bookingStatusLabel = '<span class="label label-success">'.__("admin.bookingFix").'</span>';
                        }
                        else if ($booking->status == '2') {
                            $bookingStatusLabel = '<span class="label label-danger">'.__("admin.cancelled").'</span>';
                        }
                        else if ($booking->status == '3') {
                            $bookingStatusLabel = '<span class="label label-primary">'.__("admin.completed").'</span>';
                        }
                        else if ($booking->status == '4') {
                            $bookingStatusLabel = '<span class="label label-info">'.__("admin.request").'</span>';
                        }
                        else if ($booking->status == '5') {
                            $bookingStatusLabel = '<span class="label label-warning">'.__("admin.bookingWaiting").'</span>';
                        }
                        else {
                            $bookingStatusLabel = $noData;
                        }
                        /* Condition for payment status end */

                        /* Checking checkin_from, reserve_to and booking date fields are available or not begin*/
                        if(!$booking->checkin_from){
                            $checkin_from = $noData;
                        }
                        else {
                            $checkin_from = ($booking->checkin_from)->format('d.m.y');
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
                        /* Checking checkin_from, reserve_to and booking date fields are available or not end*/

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
                        /* Condition to check user details null or not end */

                        /* Checking comment not empty or not */
                        if( !empty($booking->comments) ) {
                            $invoiceNumber_comment = '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a> <i class="fa fa-comment" data-toggle="tooltip" data-placement="top" title="'.$booking->comments.'"></i>';
                        }
                        else {
                            $invoiceNumber_comment = '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                        }

                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                        $nestedData['invoice_number']          = $invoiceNumber_comment;
                        $nestedData['usrLastname']             = $last_name;
                        $nestedData['usrFirstname']            = $first_name;
                        $nestedData['usrEmail']                = $user_email;
                        $nestedData['checkin_from']            = $checkin_from;
                        $nestedData['reserve_to']              = $reserve_to;
                        $nestedData['sleeps']                  = $booking->sleeps;
                        $nestedData['status']                  = $bookingStatusLabel;
                        $nestedData['prepayment_amount']       = $booking->prepayment_amount;
                        $nestedData['answered']                = '<button type="button" class="btn btn-default">Answer</button>';
                        $nestedData['action']                  = '<button type="button" class="btn btn-danger">Cancel</button>';
                        $data[]                                = $nestedData;
                    }
                }
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