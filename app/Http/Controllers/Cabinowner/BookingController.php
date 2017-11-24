<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CabinownerBookingRequest;
use App\Booking;
use App\Userlist;
use App\Tempuser;
use App\Cabin;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Bmessages;
use App\Season;
use DateTime;
use DatePeriod;
use DateInterval;

class BookingController extends Controller
{
    /**
     * No of beds, dorms and sleeps.
     *
     */
    public function noBedsDormsSleeps()
    {
        $numbers = array(
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '10' => 10,
            '11' => 11,
            '12' => 12,
            '13' => 13,
            '14' => 14,
            '15' => 15,
            '16' => 16,
            '17' => 17,
            '18' => 18,
            '19' => 19,
            '20' => 20,
            '21' => 21,
            '22' => 22,
            '23' => 23,
            '24' => 24,
            '25' => 25,
            '26' => 26,
            '27' => 27,
            '28' => 28,
            '29' => 29,
            '30' => 30,
        );

        return $numbers;
    }

    /**
     * To generate date format as mongo.
     *
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    protected function getDateUtc($date)
    {
        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime         = new DateTime($dateFormatChange);
        $timeStamp        = $dateTime->getTimestamp();
        $utcDateTime      = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);
        return $utcDateTime;
    }

    /**
     * To generate date between two dates.
     *
     * @param  string  $now
     * @param  string  $end
     * @return \Illuminate\Http\Response
     */
    protected function generateDates($now, $end){
        $period = new DatePeriod(
            new DateTime($now),
            new DateInterval('P1D'),
            new DateTime($end)
        );

        return $period;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($bookId = null)
    {
        if($bookId != null){
            return view('cabinowner.bookings', ['bookId' => $bookId]);
        }
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
            7 => 'beds',
            8 => 'dormitory',
            9 => 'sleeps',
            10 => 'status',
            11 => 'prepayment_amount',
            12 => 'answered'
        );

        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin)
            {
                $cabin_name = $cabin->name;

                if($request->parameterId)
                {
                    $totalData = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where('typeofbooking', 1)
                        ->where('status', "5")
                        ->where('inquirystatus', 1)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->parameterId))
                        ->count();
                    $q         = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where('typeofbooking', 1)
                        ->where('status', "5")
                        ->where('inquirystatus', 1)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->parameterId));
                }
                else {
                    $totalData = Booking::where('is_delete', 0)
                        ->where('cabinname', $cabin_name)
                        ->where('status', '!=', '7')
                        ->count();
                    $q         = Booking::where('is_delete', 0)
                        ->where('status', '!=', '7')
                        ->where('cabinname', $cabin_name);
                }

                $totalFiltered = $totalData;
                $limit         = (int)$request->input('length');
                $start         = (int)$request->input('start');
                $order         = $columns[$params['order'][0]['column']]; //contains column index
                $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

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

                        /* Search (lastname firstname email) checking in temp user table begin */
                        $tempUser    = Tempuser::where(function($query) use ($search) {
                            $query->where('usrEmail', 'like', "%{$search}%")
                                ->orWhere('usrFirstname', 'like', "%{$search}%")
                                ->orWhere('usrLastname', 'like', "%{$search}%");
                        })
                            ->skip($start)
                            ->take($limit)
                            ->get();

                        if(count($tempUser) > 0) {
                            foreach ($tempUser as $temp) {
                                $q->where('temp_user_id', $temp->_id);

                                $totalFiltered = $q->where('temp_user_id', $temp->_id)
                                    ->count();
                            }
                        }
                        /* Search (lastname firstname email) checking in temp user table end */
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

                /* thead search functionality for booking number, email, status begin */
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

                if( isset($params['columns'][10]['search']['value']) )
                {
                    $q->where(function($query) use ($params) {
                        $query->where('status', "{$params['columns'][10]['search']['value']}");
                    });

                    $totalFiltered = $q->where(function($query) use ($params) {
                        $query->where('status', "{$params['columns'][10]['search']['value']}");
                    })
                        ->count();
                }

                if( !empty($params['columns'][4]['search']['value']) )
                {
                    $users     = Userlist::where(function($query) use ($params) {
                        $query->where('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
                    })
                        ->skip($start)
                        ->take($limit)
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
                        /* Search email checking in temp user table begin */
                        $tempUser    = Tempuser::where(function($query) use ($params) {
                            $query->where('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
                        })
                            ->skip($start)
                            ->take($limit)
                            ->get();

                        if(count($tempUser) > 0) {
                            foreach ($tempUser as $user) {
                                $q->where(function($query) use ($user) {
                                        $query->where('temp_user_id', $user->_id);
                                    });

                                $totalFiltered = $q->where(function($query) use ($user) {
                                        $query->where('temp_user_id', $user->_id);
                                    })
                                    ->count();
                            }
                        }
                        /* Search email checking in temp user table end */
                    }
                }
                /* thead search functionality for booking number, email, status end */

                $bookings      = $q->skip($start)
                    ->take($limit)
                    ->orderBy($order, $dir)
                    ->get();

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
                                $usrEmail                              = $tempUser->usrEmail;
                                $bookings[$key]['bookedBy']            = '<span class="badge" data-toggle="tooltip" data-placement="top" title="'.__('cabinowner.bookedByCabinOwner').'">BCO</span>';
                                $bookings[$key]['usrEmail']            = $usrEmail;
                                $bookings[$key]['usrFirstname']        = $tempUser->usrFirstname;
                                $bookings[$key]['usrLastname']         = $tempUser->usrLastname;
                                $bookings[$key]['usrCity']             = $tempUser->usrCity;
                                $bookings[$key]['usrAddress']          = $tempUser->usrAddress;
                                $bookings[$key]['usrTelephone']        = $tempUser->usrTelephone;
                                $bookings[$key]['usrMobile']           = $tempUser->usrMobile;
                                $bookings[$key]['usrZip']              = $tempUser->usrZip;
                                $bookings[$key]['cancel']              = '<div class="row cancelDiv"><div class="col-md-12"><ul class="list-group"><li class="list-group-item"><label>'.__("cabinowner.action").'</label> <button type="button" class="btn btn-danger btn-sm cancel"><span data-cancel="'.$booking->_id.'" class="spanCancel"></span>Stornieren</button></li></ul></div></div>';
                            }
                        }
                        else{
                            $users = Userlist::where('_id', $booking->user)
                                ->get();
                            foreach ($users as $user){
                                $usrEmail                              = $user->usrEmail;
                                $bookings[$key]['bookedBy']            = '';
                                $bookings[$key]['usrEmail']            = $usrEmail;
                                $bookings[$key]['usrFirstname']        = $user->usrFirstname;
                                $bookings[$key]['usrLastname']         = $user->usrLastname;
                                $bookings[$key]['usrCity']             = $user->usrCity;
                                $bookings[$key]['usrAddress']          = $user->usrAddress;
                                $bookings[$key]['usrTelephone']        = $user->usrTelephone;
                                $bookings[$key]['usrMobile']           = $user->usrMobile;
                                $bookings[$key]['usrZip']              = $user->usrZip;
                                $bookings[$key]['cancel']              = '';
                            }
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
                        /* Condition for booking status end */

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

                        /* Condition to check user details null or not end */

                        /* Checking comment not empty or not */
                        if( !empty($booking->comments) ) {
                            $invoiceNumber_comment = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a> <i class="fa fa-comment" data-toggle="tooltip" data-placement="top" title="'.$booking->comments.'"></i>';

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
                                $messageStatus = '<a class="btn bg-purple" data-toggle="modal" data-target="#messageModal_'.$booking->_id.'"><i class="fa fa-envelope"></i></a><div class="modal fade" id="messageModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.sendMessageHead").'</h4></div><div class="alert alert-success alert-message" style="display: none;"><h4><i class="icon fa fa-check"></i> '.__("cabinowner.wellDone").' </h4>'.__("cabinowner.sendMessageSuccessResponse").'</div><div class="alert alert-danger alert-message-failed" style="display: none;">'.__("cabinowner.enterYourMsgAlert").' </div><div class="modal-body"><textarea class="form-control" style="min-width: 100%;" rows="3" placeholder="'.__("cabinowner.enterYourMsg").'" id="messageTxt_'.$booking->_id.'"></textarea></div><div class="modal-footer"><input class="message_status_update"  type="hidden" name="message_text" value="'.$booking->_id.'" data-id="'.$booking->_id.'" /><button type="button" data-loading-text="'.__("cabinowner.sendingProcess").'" autocomplete="off" class="btn bg-purple messageStatusUpdate">'.__("cabinowner.sendButton").'</button></div></div></div></div>';
                            }

                        }
                        else {
                            $invoiceNumber_comment = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';

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
                            $sleeps    = '----';
                        }
                        if(empty($booking->beds)){
                            $beds      = '----';
                        }
                        if(empty($booking->dormitory)){
                            $dormitory = '----';
                        }

                        /* Condition for, When booking status is already cancelled then disable cancel button */
                        if($booking->status == "2")
                        {
                            $bookings[$key]['cancel']          = '';
                        }


                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" /><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.moreDetails").'</h4><div class="response"></div></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.address").'</h4><p class="list-group-item-text">'.$usr_address.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.city").'</h4><p class="list-group-item-text">'.$usr_city.'</p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.usrZip").'</h4><p class="list-group-item-text">'.$usr_zip.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.telephone").'</h4><p class="list-group-item-text">'.$usr_telephone.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.mobile").'</h4><p class="list-group-item-text">'.$usr_mobile.'</p></li></ul></div></div>'.$bookings[$key]['cancel'].'</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
                        $nestedData['invoice_number']          = $invoiceNumber_comment;
                        $nestedData['usrLastname']             = $last_name;
                        $nestedData['usrFirstname']            = $first_name;
                        $nestedData['usrEmail']                = $user_email .' '. $bookings[$key]['bookedBy'];
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

        if( !empty($array['comment']) && !empty($user_email))
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
                $message->to($user_email)->subject('Nachricht von '.$booking->cabinname);
            });
            /* Functionality to send message to user end */

            $message = 'success';
        }
        else {
            $message = '';
        }

        return response()->json(['message' => $message], 201);
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelBooking(Request $request)
    {
        $booking            = Booking::findOrFail($request->data);
        $booking->status    = '2';
        $booking->save();

        return response()->json(['message' => __('cabinowner.successfullyCancelled')], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $noBedsDormsSleeps = $this->noBedsDormsSleeps();
        return view('cabinowner.createBooking', ['noBedsDormsSleeps' => $noBedsDormsSleeps]);
    }

    /**
     * Get available data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $monthBegin             = date("Y-m-d");
        $dateEndWithTime        = date("Y-m-t 23:59:59"); // To include the end date we need to add the time
        $monthEnd               = date('Y-m-d', strtotime('+1 month'));

        $holiday_prepare        = [];
        $holidays               = [];

        $cabinBeds              = '';
        $cabinDorms             = '';
        $cabinSleeps            = '';

        $dorms                  = [];
        $beds                   = [];
        $sleeps                 = [];

        $bookSleeps             = '';
        $bookBeds               = '';
        $bookDorms              = '';

        $limit                  = '';
        $bookings               = '';

        $seasons                = Season::where('cabin_owner', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->where('cabin_id', new \MongoDB\BSON\ObjectID(session('cabin_id')))
            ->get();

        $generateDates          = $this->generateDates($monthBegin, $monthEnd);

        foreach ($generateDates as $generateDate) {
            $dates = $generateDate->format('Y-m-d');
            $day   = $generateDate->format('D');
            foreach($seasons as $season) {
                if($season->summerSeasonStatus === 'open' && $season->summerSeason === 1) {
                    if(($dates >= ($season->earliest_summer_open)->format('Y-m-d')) && ($dates < ($season->latest_summer_close)->format('Y-m-d')))
                    {
                        //print_r('booked on summer season');
                        $holiday_prepare[] = ($season->summer_mon === 1) ? 'Mon' : 0;
                        $holiday_prepare[] = ($season->summer_tue === 1) ? 'Tue' : 0;
                        $holiday_prepare[] = ($season->summer_wed === 1) ? 'Wed' : 0;
                        $holiday_prepare[] = ($season->summer_thu === 1) ? 'Thu' : 0;
                        $holiday_prepare[] = ($season->summer_fri === 1) ? 'Fri' : 0;
                        $holiday_prepare[] = ($season->summer_sat === 1) ? 'Sat' : 0;
                        $holiday_prepare[] = ($season->summer_sun === 1) ? 'Sun' : 0;
                        /* 1   0000 1   0 1   00000 1   1   00000 1 */
                        /* Mon 0000 Sat 0 Mon 00000 Sun Mon 00000 Sun */
                    }
                }

                if($season->winterSeasonStatus === 'open' && $season->winterSeason === 1) {
                    if(($dates >= ($season->earliest_winter_open)->format('Y-m-d')) && ($dates < ($season->latest_winter_close)->format('Y-m-d')))
                    {
                        //print_r('booked on winter season');
                        $holiday_prepare[] = ($season->winter_mon === 1) ? 'Mon' : 0;
                        $holiday_prepare[] = ($season->winter_tue === 1) ? 'Tue' : 0;
                        $holiday_prepare[] = ($season->winter_wed === 1) ? 'Wed' : 0;
                        $holiday_prepare[] = ($season->winter_thu === 1) ? 'Thu' : 0;
                        $holiday_prepare[] = ($season->winter_fri === 1) ? 'Fri' : 0;
                        $holiday_prepare[] = ($season->winter_sat === 1) ? 'Sat' : 0;
                        $holiday_prepare[] = ($season->winter_sun === 1) ? 'Sun' : 0;
                        /* 000000 1   0 1   00000 1   000000 */
                        /* 000000 Sun 0 Tue 00000 Mon 000000 */
                    }
                }
            }

            $prepareArray           = [$dates => $day];
            $array_unique           = array_unique($holiday_prepare);
            $array_intersect        = array_intersect($prepareArray,$array_unique);
            foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                $holidays[] = $array_intersect_key;
            }
        }

        if($request->search == 'searchAvailability') {
            if(session('sleeping_place') != 1)
            {
                $this->validate($request, [
                    'daterange' => 'required',
                    'beds' => 'required_without:dorms',
                    'dorms' => 'required_without:beds',
                ]);
            }
            else {
                $this->validate($request, [
                    'daterange' => 'required',
                    'sleeps' => 'required|not_in:0',
                ]);
            }

            if($request->daterange != null){
                $daterange              = explode(" - ", $request->daterange);
                $dateBeginUtc           = $this->getDateUtc($daterange[0]);
                $dateEndUtc             = $this->getDateUtc($daterange[1]);
                $dateBegin              = DateTime::createFromFormat('d.m.y', $daterange[0])->format('Y-m-d');
                $dateEnd                = DateTime::createFromFormat('d.m.y', $daterange[1])->format('Y-m-d');
                $dateDifference         = date_diff(date_create($dateBegin), date_create($dateEnd));

                $generateBookingDates   = $this->generateDates($dateBegin, $dateEnd);

                foreach ($generateBookingDates as $key => $generateBookingDate) {
                    if($dateDifference->format("%a") <= 60) {

                        /* Getting count of sleeps, beds and dorms from bookings. Getting booking status is 1=>Fix, 5=>Waiting for payment, 4=>Request, 7=>Inquiry */
                        $bookings  = Booking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabinname', session('cabin_name'))
                            ->whereIn('status', ['1', '4', '5', '7'])
                            ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        /*$bookings  = Booking::raw(function ($collection) use ($generateBookingDate) {
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'cabinname' => session('cabin_name'),
                                        'checkin_from' => ['$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y'))],
                                        'reserve_to' => ['$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y'))],
                                        'is_delete' => 0,
                                    ]
                                ],
                                [
                                    '$group' => [
                                        '_id' => ['checkin_from' => '$checkin_from'],
                                        'beds' => ['$sum' => '$beds'],
                                        'sum' => ['$sum' => 1]
                                    ]
                                ],
                                [
                                    '$project' => [
                                        'beds' => 1,
                                        'dormitory' => 1,
                                        'sleeps' => 1,
                                        'status' => 1,
                                        'sum' => 1,
                                        'hasstatus' => [
                                            '$in' => ['status', ['1', '4', '5', '7']]
                                        ]
                                    ],
                                ]
                            ]);
                        });*/

                        print_r($bookings->sum('sleeps'));


                        /*if(count($bookings) > 0) {
                            foreach ($bookings as $booking) {
                                if($booking->dormitory != '') {
                                    $dorms[]  = $booking->dormitory;
                                }

                                if($booking->beds != '') {
                                    $beds[]   = $booking->beds;
                                }

                                if($booking->sleeps != '') {
                                    $sleeps[] = $booking->sleeps;
                                }
                            }
                        }*/

                        /* Taking beds, dorms and sleeps depends up on sleeping_place */
                        /*if(session('sleeping_place') != 1) {
                            $cabinBeds  = session('beds');
                            $cabinDorms = session('dormitory');
                            $bookBeds   = array_sum($beds);
                            $bookDorms  = array_sum($dorms);
                            print_r(' Beds:'.$request->beds.' CabinBeds:'.$cabinBeds.' bookBeds:'.$bookBeds.' Dorms:'.$request->dorms.' CabinDorms:'.$cabinDorms.' BookDorms:'.$bookDorms);
                        }
                        else {
                            $cabinSleeps = session('sleeps');
                            $bookSleeps  = array_sum($sleeps);
                            print_r('sleeps'.$request->sleeps.' CabinSleeps:'.$cabinSleeps.' BookSleeps:'.$bookSleeps);
                        }*/


                    }
                    else {
                        $limit = 'Quota exceeded';
                    }
                }
            }
        }
        //exit();
        return response()->json(['holidays' => $holidays, 'limit' => $limit]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CabinownerBookingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CabinownerBookingRequest $request)
    {
        //return redirect()->back();
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
