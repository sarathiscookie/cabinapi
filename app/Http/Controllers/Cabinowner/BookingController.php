<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Userlist;
use App\Tempuser;
use App\Cabin;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Bmessages;

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
            10 => 'answered'
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

                    /* Date range func begin */
                    if($request->input('is_date_search') == 'yes')
                    {
                        //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
                        $checkin_from           = explode("-", $request->input('daterange'));
                        $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
                        $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);

                        $bookings = Booking::where('is_delete', 0)
                            ->where('cabinname', $cabin_name)
                            ->whereBetween('checkin_from', array($dateBegin, $dateEnd))
                            ->where(function($query) use ($params, $dateBegin, $dateEnd) {
                                $query->whereBetween('reserve_to', array($dateBegin, $dateEnd));
                            })
                            ->skip($start)
                            ->take($limit)
                            ->orderBy($order, $dir)
                            ->get();

                        $totalFiltered = Booking::where('is_delete', 0)
                            ->where('cabinname', $cabin_name)
                            ->whereBetween('checkin_from', array($dateBegin, $dateEnd))
                            ->where(function($query) use ($params, $dateBegin, $dateEnd) {
                                $query->whereBetween('reserve_to', array($dateBegin, $dateEnd));
                            })
                            ->count();
                    }

                    if($request->input('is_date_search') == 'no')
                    {
                        $bookings = Booking::where('is_delete', 0)
                            ->where('cabinname', $cabin_name)
                            ->skip($start)
                            ->take($limit)
                            ->orderBy($order, $dir)
                            ->get();
                    }
                    /* Date range func end */

                }
                else {
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

                        /* Search (lastname firstname email) checking in temp user table begin */
                        $tempUser    = Tempuser::where(function($query) use ($search) {
                            $query->where('usrEmail', 'like', "%{$search}%")
                                ->orWhere('usrFirstname', 'like', "%{$search}%")
                                ->orWhere('usrLastname', 'like', "%{$search}%");
                        })
                            ->skip($start)
                            ->take($limit)
                            ->orderBy($order, $dir)
                            ->get();

                        if(count($tempUser) > 0) {
                            foreach ($tempUser as $temp) {
                                $bookings = Booking::where('is_delete', 0)
                                    ->where('cabinname', $cabin_name)
                                    ->where('temp_user_id', $temp->_id)
                                    ->skip($start)
                                    ->take($limit)
                                    ->orderBy($order, $dir)
                                    ->get();

                                $totalFiltered = Booking::where('is_delete', 0)
                                    ->where('cabinname', $cabin_name)
                                    ->where('temp_user_id', $temp->_id)
                                    ->count();

                            }
                        }
                        /* Search (lastname firstname email) checking in temp user table end */
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
                    $users     = Userlist::where(function($query) use ($params) {
                        $query->where('usrLastname', 'like', "%{$params['columns'][2]['search']['value']}%")
                            ->orWhere('usrFirstname','like', "%{$params['columns'][3]['search']['value']}%")
                            ->orWhere('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
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
                        /* Search (lastname firstname email) checking in temp user table begin */
                        $tempUser    = Tempuser::where(function($query) use ($params) {
                            $query->where('usrLastname', 'like', "%{$params['columns'][2]['search']['value']}%")
                                ->orWhere('usrFirstname','like', "%{$params['columns'][3]['search']['value']}%")
                                ->orWhere('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
                        })
                            ->skip($start)
                            ->take($limit)
                            ->orderBy($order, $dir)
                            ->get();

                        if(count($tempUser) > 0) {
                            foreach ($tempUser as $user) {
                                $bookings = Booking::where('is_delete', 0)
                                    ->where('cabinname', $cabin_name)
                                    ->where(function($query) use ($user) {
                                        $query->where('temp_user_id', $user->_id);
                                    })
                                    ->skip($start)
                                    ->take($limit)
                                    ->orderBy($order, $dir)
                                    ->get();

                                $totalFiltered = Booking::where('is_delete', 0)
                                    ->where('cabinname', $cabin_name)
                                    ->where(function($query) use ($user) {
                                        $query->where('temp_user_id', $user->_id);
                                    })
                                    ->count();
                            }
                        }
                        /* Search (lastname firstname email) checking in temp user table end */
                    }
                }
                /* thead search functionality for booking number, lastname, firstname, email end */


                $data          = array();
                $noData        = '<span class="label label-default">'.__("cabinowner.noResult").'</span>';
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

                        /* Checking booking done by cabin owner */
                        if($bookings[$key]['bookedBy'] == 'cabinowner') {
                            $bookedBy = '<span class="badge" data-toggle="tooltip" data-placement="top" title="'.__('cabinowner.bookedByCabinOwner').'">BCO</span>';
                        }
                        else {
                            $bookedBy = '';
                        }

                        /* Condition for booking status begin */
                        if($booking->status == '1') {
                            $bookingStatusLabel = '<span class="label label-success">'.__("cabinowner.bookingFix").'</span>';
                        }
                        else if ($booking->status == '2') {
                            $bookingStatusLabel = '<span class="label label-danger">'.__("cabinowner.cancelled").'</span>';
                        }
                        else if ($booking->status == '3') {
                            $bookingStatusLabel = '<span class="label label-primary">'.__("cabinowner.completed").'</span>';
                        }
                        else if ($booking->status == '4') {
                            $bookingStatusLabel = '<span class="label label-info">'.__("cabinowner.request").'</span>';
                        }
                        else if ($booking->status == '5') {
                            $bookingStatusLabel = '<span class="label label-warning">'.__("cabinowner.bookingWaiting").'</span>';
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
                            $invoiceNumber_comment = '<a class="nounderline">'.$booking->invoice_number.'</a> <i class="fa fa-comment" data-toggle="tooltip" data-placement="top" title="'.$booking->comments.'"></i>';

                            /*Condition to check cabin owner answered*/
                            $messages    = Bmessages::where('is_delete', 0)
                                ->where('booking_id', $booking->_id)
                                ->whereNotNull('comment')
                                ->first();

                            if (count($messages) > 0) {
                                if ($messages->comment != '') {
                                    $messageStatus = '<i class="fa fa-fw fa-check"></i><i class="fa fa-comment" data-toggle="tooltip" data-placement="top" title="' . $messages->comment . '"></i>';
                                }
                                else {
                                    $messageStatus = '<span class="label label-warning">'.__("cabinowner.emptyMessage").'</span>';
                                }
                            }
                            else {
                                $messageStatus = '<a class="btn bg-purple" data-toggle="modal" data-target="#messageModal_'.$booking->_id.'"><i class="fa fa-envelope"></i></a><div class="modal fade" id="messageModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.sendMessageHead").'</h4></div><div class="alert alert-success alert-message" style="display: none;"><h4><i class="icon fa fa-check"></i> '.__("cabinowner.wellDone").' </h4>'.__("cabinowner.sendMessageSuccessResponse").'</div><div class="alert alert-danger alert-message-failed" style="display: none;">'.__("cabinowner.enterYourMsg").' </div><div class="modal-body"><textarea class="form-control" style="min-width: 100%;" rows="3" placeholder="'.__("cabinowner.enterYourMsg").'" id="messageTxt_'.$booking->_id.'"></textarea></div><div class="modal-footer"><input class="message_status_update"  type="hidden" name="message_text" value="'.$booking->_id.'" data-id="'.$booking->_id.'" /><button type="button" data-loading-text="'.__("cabinowner.sendingProcess").'" autocomplete="off" class="btn bg-purple messageStatusUpdate">'.__("cabinowner.sendButton").'</button></div></div></div></div>';
                            }

                        }
                        else {
                            $invoiceNumber_comment = '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';

                            /*Condition to check cabin owner answered*/
                            $messageStatus = '<span class="label label-default">'.__("cabinowner.notAsked").'</span>';
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
                            $sleeps    = '-----';
                        }
                        if(empty($booking->beds)){
                            $beds      = '-----';
                        }
                        if(empty($booking->dormitory)){
                            $dormitory = '-----';
                        }


                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                        $nestedData['invoice_number']          = $invoiceNumber_comment;
                        $nestedData['usrLastname']             = $last_name;
                        $nestedData['usrFirstname']            = $first_name;
                        $nestedData['usrEmail']                = $user_email .' '. $bookedBy;
                        $nestedData['checkin_from']            = $checkin_from;
                        $nestedData['reserve_to']              = $reserve_to;
                        $nestedData['beds']                    = $beds;
                        $nestedData['dormitory']               = $dormitory;
                        $nestedData['sleeps']                  = $sleeps;
                        $nestedData['status']                  = $bookingStatusLabel;
                        $nestedData['prepayment_amount']       = $amount;
                        $nestedData['answered']                = $messageStatus;
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
     * send message to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $message    = '';
        $array      = json_decode($request->data, true);
        $id         = $array['id'];

        $booking    = Booking::where('is_delete', 0)
            ->where('_id', $id)
            ->first();

        if(count($booking) > 0) {
            if($booking->temp_user_id != ""){
                $tempUser  = Tempuser::where('_id', $booking->temp_user_id)
                    ->first();
                $user_id   = $tempUser->_id;
                $user_email= $tempUser->usrEmail;
            }
            else{
                $user      = Userlist::where('_id', $booking->user)
                    ->first();
                $user_id   = $user->_id;
                $user_email= $user->usrEmail;
            }
        }

        /* If already message with same booking id delete old message */
        Bmessages::where('booking_id', $id)
            ->delete();

        if(!empty($array['comment']))
        {
            $messages               = new Bmessages;
            $messages->booking_id   = $id;
            $messages->cabinuser    = Auth::user()->_id;
            $messages->guest        = $user_id;
            $messages->comment      = $array['comment'];
            $messages->is_delete    = 0;
            $messages->save();

            /* Functionality to send message to user begin */
            Mail::send('emails.cabinOwnerSendMessage', ['comment' => $array['comment'], 'cabinName' => $booking->cabinname, 'subject' => 'Nachricht von ', 'email' => $user_email], function ($message) use ($user_email, $booking) {
                $message->to('iamsarath1986@gmail.com')->subject('Nachricht von '.$booking->cabinname);
            });
            /* Functionality to send message to user end */

            $message = 'success';
        }

        return response()->json(['message' => $message], 201);
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
