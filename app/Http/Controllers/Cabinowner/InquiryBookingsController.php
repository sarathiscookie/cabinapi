<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryBookingRequest;
use App\Cabin;
use App\Booking;
use App\Userlist;
use Auth;
use Mail;

class InquiryBookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cabinowner.inquiryBookings');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\InquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(InquiryBookingRequest $request)
    {
        $params        = $request->all();

        $columns       = array(
            1 => 'invoice_number',
            2 => 'usrLastname',
            3 => 'usrFirstname',
            4 => 'usrEmail',
            5 => 'checkin_from',
            6 => 'reserve_to',
            7 => 'beds',
            8 => 'dormitory',
            9 => 'sleeps',
            10 => 'prepayment_amount',
            11 => 'answered',
            12 => 'inquirystatus'
        );

        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin)
            {
                $cabin_name = $cabin->name;
                $totalData  = Booking::where('is_delete', 0)
                    ->where('cabinname', $cabin_name)
                    ->where('typeofbooking', 1)
                    ->where('status', "7")
                    ->count();

                $totalFiltered = $totalData;
                $limit         = (int)$request->input('length');
                $start         = (int)$request->input('start');
                $order         = $columns[$params['order'][0]['column']]; //contains column index
                $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

                $q             = Booking::where('is_delete', 0)
                    ->where('typeofbooking', 1)
                    ->where('status', "7")
                    ->where('cabinname', $cabin_name);

                if(!empty($request->input('search.value')))
                {
                    $search   = $request->input('search.value');

                    /* Checking email: Reason for using this method is because mongo $lookup is not working. Reason is user._id is objectid and booking.user is a string */
                    $users     = Userlist::where(function($query) use ($search) {
                        $query->where('usrEmail', 'like', "%{$search}%")
                            ->orWhere('usrFirstname', 'like', "%{$search}%")
                            ->orWhere('usrLastname', 'like', "%{$search}%");
                    })
                        ->get();

                    if(count($users) > 0) {
                        foreach ($users as $user) {
                            $q->where(function($query) use ($user) {
                                $query->where('user', new \MongoDB\BSON\ObjectID($user->_id));
                            });

                            $totalFiltered = $q->where(function($query) use ($user) {
                                $query->where('user', new \MongoDB\BSON\ObjectID($user->_id));
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

                    $q->whereBetween('checkin_from', [$dateBegin, $dateEnd]);

                    $totalFiltered = $q->whereBetween('checkin_from', [$dateBegin, $dateEnd])
                        ->count();
                }
                /* Date range func end */

                /* tfoot search functionality for booking number, email, status begin */
                if( !empty($params['columns'][1]['search']['value']) )
                {
                    $q->where(function($query) use ($params) {
                        $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
                    });

                    $totalFiltered = $q->where(function($query) use ($params) {
                        $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
                    })
                        ->count();
                }

                if( !empty($params['columns'][4]['search']['value']) )
                {
                    $users     = Userlist::where(function($query) use ($params) {
                        $query->where('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
                    })
                        ->get();

                    if(count($users) > 0) {
                        foreach ($users as $user) {
                            $q->where(function($query) use ($user) {
                                $query->where('user', new \MongoDB\BSON\ObjectID($user->_id));
                            });

                            $totalFiltered = $q->where(function($query) use ($user) {
                                $query->where('user', new \MongoDB\BSON\ObjectID($user->_id));
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
                $noData        = '<span class="label label-default">'.__("inquiry.noResult").'</span>';
                if(!empty($bookings)) {
                    foreach ($bookings as $key => $booking)
                    {
                        $users = Userlist::where('_id', $booking->user)
                            ->get();
                        foreach ($users as $user){
                            $bookings[$key]['usrEmail']            = $user->usrEmail;
                            $bookings[$key]['usrFirstname']        = $user->usrFirstname;
                            $bookings[$key]['usrLastname']         = $user->usrLastname;
                            $bookings[$key]['usrCity']             = $user->usrCity;
                            $bookings[$key]['usrAddress']          = $user->usrAddress;
                            $bookings[$key]['usrTelephone']        = $user->usrTelephone;
                            $bookings[$key]['usrMobile']           = $user->usrMobile;
                            $bookings[$key]['usrZip']              = $user->usrZip;
                        }

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

                        /* Condition for prepay amount */
                        if(!$booking->prepayment_amount) {
                            $amount = '00.00<i class="fa fa-fw fa-eur"></i>';
                        }
                        else {
                            $amount = number_format($booking->prepayment_amount, 2).'<i class="fa fa-fw fa-eur"></i>';
                        }

                        /* Condition for beds, dorms and sleeps */
                        if(empty($booking->beds) && empty($booking->dormitory))
                        {
                            $sleeps    = $booking->sleeps;
                        }
                        else {
                            $beds      = $booking->beds;
                            $dormitory = $booking->dormitory;
                            $sleeps    = '----';
                        }
                        if(empty($booking->beds)){
                            $beds      = '----';
                        }
                        if(empty($booking->dormitory)){
                            $dormitory = '----';
                        }

                        /* Condition for inquiry status */
                        if($booking->inquirystatus == 0){
                            $inquiryStatus = '<button type="button" class="btn btn-success btn-xs approve" data-approve="'.$booking->_id.'"><i class="fa fa-fw fa-check"></i></button> <button type="button" class="btn btn-danger btn-xs reject" data-reject="'.$booking->_id.'"><i class="fa fa-fw fa-close"></i></button>';
                        }
                        else if($booking->inquirystatus == 2){
                            $inquiryStatus = '<span class="label label-danger">'.__("inquiry.rejected").'</span>';
                        }
                        else{
                            $inquiryStatus = '<span class="label label-default">'.__("inquiry.noResult").'</span>';
                        }


                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" /><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("inquiry.moreDetails").'</h4><div class="response"></div></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.address").'</h4><p class="list-group-item-text">'.$usr_address.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.city").'</h4><p class="list-group-item-text">'.$usr_city.'</p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.usrZip").'</h4><p class="list-group-item-text">'.$usr_zip.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.telephone").'</h4><p class="list-group-item-text">'.$usr_telephone.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.mobile").'</h4><p class="list-group-item-text">'.$usr_mobile.'</p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
                        $nestedData['invoice_number']          = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                        $nestedData['usrLastname']             = $last_name;
                        $nestedData['usrFirstname']            = $first_name;
                        $nestedData['usrEmail']                = $user_email;
                        $nestedData['checkin_from']            = $checkin_from;
                        $nestedData['reserve_to']              = $reserve_to;
                        $nestedData['beds']                    = $beds;
                        $nestedData['dormitory']               = $dormitory;
                        $nestedData['sleeps']                  = $sleeps;
                        $nestedData['prepayment_amount']       = $amount;
                        $nestedData['answered']                = '<span class="label label-default">'.__("inquiry.notAsked").'</span>';
                        $nestedData['inquirystatus']           = $inquiryStatus;
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
     *  @param  \App\Http\Requests\InquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function approveStatus(InquiryBookingRequest $request)
    {
        $inquiry                = Booking::findOrFail($request->data);
        $inquiry->inquirystatus = 1; //0 = waiting, 1 = Approved, 2 = Rejected
        $inquiry->status        = '5';
        $inquiry->save();

        $user                   = Userlist::where('_id', $inquiry->user)
            ->first();
        $user_email             = $user->usrEmail;

        /* Functionality to send inquiry status approval message to guest begin*/
        $comment                = 'Congratulations! Your enquiry for Checking from: '.($inquiry->checkin_from)->format('d.m.y').', To: '.($inquiry->reserve_to)->format('d.m.y').' and Sleeps: '.$inquiry->sleeps.' has been approved. Please login to website for more details.';
        Mail::send('emails.inquiryStatusMessage', ['comment' => $comment, 'cabinName' => $inquiry->cabinname, 'subject' => 'Nachricht von ', 'email' => 'iamsarath1986@gmail.com'], function ($message) use ($user_email, $inquiry) {
            $message->to('iamsarath1986@gmail.com')->subject('Nachricht von '.$inquiry->cabinname);
        });
        /* Functionality to send inquiry status approval message to guest end */

        return response()->json(['statusInquiry' => __("inquiry.inquiryStatusApproved"), 'inquiryStatusApprovedSec' => __("inquiry.inquiryStatusApprovedSec"), 'dataId' => $request->data], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     *  @param  \App\Http\Requests\InquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function rejectStatus(InquiryBookingRequest $request)
    {
        $inquiry                = Booking::findOrFail($request->data);
        $inquiry->inquirystatus = 2; //0 = waiting, 1 = Approved, 2 = Rejected
        $inquiry->save();

        $user                   = Userlist::where('_id', $inquiry->user)
            ->first();
        $user_email             = $user->usrEmail;

        /* Functionality to send inquiry status approval message to guest begin*/
        $comment                = 'Your enquiry for Checking from: '.($inquiry->checkin_from)->format('d.m.y').', To: '.($inquiry->reserve_to)->format('d.m.y').' and Sleeps: '.$inquiry->sleeps.' has been rejected. Please login to website for more details.';
        Mail::send('emails.inquiryStatusMessage', ['comment' => $comment, 'cabinName' => $inquiry->cabinname, 'subject' => 'Nachricht von ', 'email' => 'iamsarath1986@gmail.com'], function ($message) use ($user_email, $inquiry) {
            $message->to('iamsarath1986@gmail.com')->subject('Nachricht von '.$inquiry->cabinname);
        });
        /* Functionality to send inquiry status approval message to guest end */
        return response()->json(['statusInquiry' => __("inquiry.inquiryStatusRejected")], 201);
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
