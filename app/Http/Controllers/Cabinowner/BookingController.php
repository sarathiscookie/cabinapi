<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CabinownerBookingRequest;
use App\Booking;
use App\Userlist;
use App\Tempuser;
use App\Cabin;
use App\MountSchoolBooking;
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
        $monthEnd               = date('Y-m-d', strtotime('+1 month'));

        $holiday_prepare        = [];
        $disableDates           = [];
        $regular_dates_array    = [];
        $not_regular_dates      = [];

        $dorms                  = 0;
        $beds                   = 0;
        $sleeps                 = 0;

        $msSleeps               = 0;
        $msBeds                 = 0;
        $msDorms                = 0;

        $limit                  = '';

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
                $disableDates[] = $array_intersect_key; // holidays
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
                        $generateBookingDat   = $generateBookingDate->format('Y-m-d'); //2017-09-02,2017-09-03,2017-09-04,2017-09-05,2017-09-06,2017-09-07,2017-09-08,2017-09-09,2017-09-10,2017-09-11
                        $generateBookingDay   = $generateBookingDate->format('D'); //Sat,Sun,Mon,Tue,Wed,Thu,Fri,Sat,Sun,Mon

                        $session_mon_day      = (session('mon_day') === 1) ? 'Mon' : 0;
                        $session_tue_day      = (session('tue_day') === 1) ? 'Tue' : 0;
                        $session_wed_day      = (session('wed_day') === 1) ? 'Wed' : 0;
                        $session_thu_day      = (session('thu_day') === 1) ? 'Thu' : 0;
                        $session_fri_day      = (session('fri_day') === 1) ? 'Fri' : 0;
                        $session_sat_day      = (session('sat_day') === 1) ? 'Sat' : 0;
                        $session_sun_day      = (session('sun_day') === 1) ? 'Sun' : 0;

                        /* Getting bookings from booking collection status is 1=>Fix, 4=>Request, 7=>Inquiry */
                        $bookings  = Booking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabinname', session('cabin_name'))
                            ->whereIn('status', ['1', '4', '7'])
                            ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        /* Getting bookings from mschool collection status is 1=>Fix, 4=>Request, 7=>Inquiry */
                        $msBookings  = MountSchoolBooking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabin_name', session('cabin_name'))
                            ->whereIn('status', ['1', '4', '7'])
                            ->whereRaw(['check_in' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        /* Getting count of sleeps, beds and dorms */
                        if(count($bookings) > 0 || count($msBookings) > 0) {
                            $sleeps   = $bookings->sum('sleeps');
                            $beds     = $bookings->sum('beds');
                            $dorms    = $bookings->sum('dormitory');
                            $msSleeps = $msBookings->sum('sleeps');
                            $msBeds   = $msBookings->sum('beds');
                            $msDorms  = $msBookings->sum('dormitory');
                        }

                        /* Taking beds, dorms and sleeps depends up on sleeping_place */
                        if(session('sleeping_place') != 1) {
                            $totalBeds  = $beds + $msBeds;
                            $totalDorms = $dorms + $msDorms;

                            /* Calculating beds & dorms of regular and not regular */
                            if ($request->session()->has('regular') || $request->session()->has('not_regular')) {

                                if(session('not_regular') === 1) {
                                    $not_regular_date_explode = explode(" - ", session('not_regular_date'));
                                    $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                    $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                    $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                    foreach($generateNotRegularDates as $generateNotRegularDate) {
                                        $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                    }

                                    //print_r($not_regular_dates); //[2017-09-01 2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02, 2017-09-01  2017-09-02]
                                    //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]
                                    if(in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalBeds < session('not_regular_beds')) {

                                            $available_not_regular_beds = session('not_regular_beds') - $totalBeds;

                                            if($request->beds <= $available_not_regular_beds) {
                                                print_r(' Not regular beds available '.' availableBeds ' . $available_not_regular_beds);
                                            }
                                            else {
                                                print_r(' Not regular beds not available '.' availableBeds ' . $available_not_regular_beds);
                                            }
                                        }
                                        else {
                                            print_r(' Not regular beds not available ');
                                        }

                                        if($totalDorms < session('not_regular_dorms')) {

                                            $available_not_regular_dorms = session('not_regular_dorms') - $totalDorms;

                                            if($request->dorms <= $available_not_regular_dorms) {
                                                print_r(' Not regular dorms available '.' availableDorms ' . $available_not_regular_dorms);
                                            }
                                            else {
                                                print_r(' Not regular dorms not available '.' availableDorms ' . $available_not_regular_dorms);
                                            }
                                        }
                                        else {
                                            print_r(' Not regular dorms not available ');
                                        }

                                        print_r(' Date '.$generateBookingDat.' not_regular_beds: '.session('not_regular_beds').' totalBeds '. $totalBeds . ' not_regular_dorms: '.session('not_regular_dorms').' totalDorms '. $totalDorms);
                                    }

                                    // print_r($regular_dates_array); //2017-09-02, 2017-09-02, 2017-09-02


                                    /*session('not_regular_beds');
                                    session('not_regular_dorms');
                                    session('not_regular_sleeps');*/
                                }

                                if(session('regular') === 1) {

                                    if($session_mon_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('mon_beds')) {

                                                $available_mon_beds = session('mon_beds') - $totalBeds;

                                                if($request->beds <= $available_mon_beds) {
                                                    print_r(' mon beds available '.' available_mon_beds ' . $available_mon_beds);
                                                }
                                                else {
                                                    print_r(' mon beds not available '.' available_mon_beds ' . $available_mon_beds);
                                                }
                                            }
                                            else {
                                                print_r(' mon beds not available ');
                                            }

                                            if($totalDorms < session('mon_dorms')) {

                                                $available_mon_dorms = session('mon_dorms') - $totalDorms;

                                                if($request->dorms <= $available_mon_dorms) {
                                                    print_r(' mon dorms available '.' available_mon_dorms ' . $available_mon_dorms);
                                                }
                                                else {
                                                    print_r(' mon dorms not available '.' available_mon_dorms ' . $available_mon_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' mon dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' mon_beds: '.session('mon_beds').' totalBeds '. $totalBeds . ' mon_dorms: '.session('mon_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_tue_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('tue_beds')) {

                                                $available_tue_beds = session('tue_beds') - $totalBeds;

                                                if($request->beds <= $available_tue_beds) {
                                                    print_r(' tue_beds available '.' available_tue_beds ' . $available_tue_beds);
                                                }
                                                else {
                                                    print_r(' tue_beds not available '.' available_tue_beds ' . $available_tue_beds);
                                                }
                                            }
                                            else {
                                                print_r(' tue_beds not available ');
                                            }

                                            if($totalDorms < session('tue_dorms')) {

                                                $available_tue_dorms = session('tue_dorms') - $totalDorms;

                                                if($request->dorms <= $available_tue_dorms) {
                                                    print_r(' tue_dorms available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                }
                                                else {
                                                    print_r(' tue_dorms not available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' tue_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' tue_beds: '.session('tue_beds').' totalBeds '. $totalBeds . ' tue_dorms: '.session('tue_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_wed_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('wed_beds')) {

                                                $available_wed_beds = session('wed_beds') - $totalBeds;

                                                if($request->beds <= $available_wed_beds) {
                                                    print_r(' wed_beds available '.' available_wed_beds ' . $available_wed_beds);
                                                }
                                                else {
                                                    print_r(' wed_beds not available '.' available_wed_beds ' . $available_wed_beds);
                                                }
                                            }
                                            else {
                                                print_r(' wed_beds not available ');
                                            }

                                            if($totalDorms < session('wed_dorms')) {

                                                $available_wed_dorms = session('wed_dorms') - $totalDorms;

                                                if($request->dorms <= $available_wed_dorms) {
                                                    print_r(' wed_dorms available '.' available_wed_dorms ' . $available_wed_dorms);
                                                }
                                                else {
                                                    print_r(' wed_dorms not available '.' available_wed_dorms ' . $available_wed_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' wed_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' wed_beds: '.session('wed_beds').' totalBeds '. $totalBeds . ' wed_dorms: '.session('wed_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_thu_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('thu_beds')) {

                                                $available_thu_beds = session('thu_beds') - $totalBeds;

                                                if($request->beds <= $available_thu_beds) {
                                                    print_r(' thu_beds available '.' available_thu_beds ' . $available_thu_beds);
                                                }
                                                else {
                                                    print_r(' thu_beds not available '.' available_thu_beds ' . $available_thu_beds);
                                                }
                                            }
                                            else {
                                                print_r(' thu_beds not available ');
                                            }

                                            if($totalDorms < session('thu_dorms')) {

                                                $available_thu_dorms = session('thu_dorms') - $totalDorms;

                                                if($request->dorms <= $available_thu_dorms) {
                                                    print_r(' thu_dorms available '.' available_thu_dorms ' . $available_thu_dorms);
                                                }
                                                else {
                                                    print_r(' thu_dorms not available '.' available_thu_dorms ' . $available_thu_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' thu_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' thu_beds: '.session('thu_beds').' totalBeds '. $totalBeds . ' thu_dorms: '.session('thu_dorms').' totalDorms '. $totalDorms);

                                        }

                                    }

                                    if($session_fri_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('fri_beds')) {

                                                $available_fri_beds = session('fri_beds') - $totalBeds;

                                                if($request->beds <= $available_fri_beds) {
                                                    print_r(' fri_beds available '.' available_fri_beds ' . $available_fri_beds);
                                                }
                                                else {
                                                    print_r(' fri_beds not available '.' available_fri_beds ' . $available_fri_beds);
                                                }
                                            }
                                            else {
                                                print_r(' fri_beds not available ');
                                            }

                                            if($totalDorms < session('fri_dorms')) {

                                                $available_fri_dorms = session('fri_dorms') - $totalDorms;

                                                if($request->dorms <= $available_fri_dorms) {
                                                    print_r(' fri_dorms available '.' available_fri_dorms ' . $available_fri_dorms);
                                                }
                                                else {
                                                    print_r(' fri_dorms not available '.' available_fri_dorms ' . $available_fri_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' fri_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' fri_beds: '.session('fri_beds').' totalBeds '. $totalBeds . ' fri_dorms: '.session('fri_dorms').' totalDorms '. $totalDorms);

                                        }

                                    }

                                    if($session_sat_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('sat_beds')) {

                                                $available_sat_beds = session('sat_beds') - $totalBeds;

                                                if($request->beds <= $available_sat_beds) {
                                                    print_r(' sat_beds available '.' available_sat_beds ' . $available_sat_beds);
                                                }
                                                else {
                                                    print_r(' sat_beds not available '.' available_sat_beds ' . $available_sat_beds);
                                                }
                                            }
                                            else {
                                                print_r(' sat_beds not available ');
                                            }

                                            if($totalDorms < session('sat_dorms')) {

                                                $available_sat_dorms = session('sat_dorms') - $totalDorms;

                                                if($request->dorms <= $available_sat_dorms) {
                                                    print_r(' sat_dorms available '.' available_sat_dorms ' . $available_sat_dorms);
                                                }
                                                else {
                                                    print_r(' sat_dorms not available '.' available_sat_dorms ' . $available_sat_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' sat_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' sat_beds: '.session('sat_beds').' totalBeds '. $totalBeds . ' sat_dorms: '.session('sat_dorms').' totalDorms '. $totalDorms);

                                        }

                                    }

                                    if($session_sun_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalBeds < session('sun_beds')) {

                                                $available_sun_beds = session('sun_beds') - $totalBeds;

                                                if($request->beds <= $available_sun_beds) {
                                                    print_r(' sun_beds available '.' available_sun_beds ' . $available_sun_beds);
                                                }
                                                else {
                                                    print_r(' sun_beds not available '.' available_sun_beds ' . $available_sun_beds);
                                                }
                                            }
                                            else {
                                                print_r(' sun_beds not available ');
                                            }

                                            if($totalDorms < session('sun_dorms')) {

                                                $available_sun_dorms = session('sun_dorms') - $totalDorms;

                                                if($request->dorms <= $available_sun_dorms) {
                                                    print_r(' sun_dorms available '.' available_sun_dorms ' . $available_sun_dorms);
                                                }
                                                else {
                                                    print_r(' sun_dorms not available '.' available_sun_dorms ' . $available_sun_dorms);
                                                }
                                            }
                                            else {
                                                print_r(' sun_dorms not available ');
                                            }

                                            print_r(' Date '.$generateBookingDat.' sun_beds: '.session('sun_beds').' totalBeds '. $totalBeds . ' sun_dorms: '.session('sun_dorms').' totalDorms '. $totalDorms );
                                        }

                                    }
                                }
                            }

                            /* Calculating beds & dorms of normal */
                            //print_r(array_unique($regular_dates_array)); //[2017-09-02, 2017-09-04] //if not regular has 2017-09-04 and regular has 2017-09-04

                            //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]

                            if(!in_array($generateBookingDat, $regular_dates_array)) {

                                if($totalBeds < session('beds')) {

                                    $availableBeds = session('beds') - $totalBeds;

                                    if($request->beds <= $availableBeds) {
                                        print_r(' Beds available '.' availableBeds ' . $availableBeds);
                                    }
                                    else {
                                        print_r(' Beds not available '.' availableBeds ' . $availableBeds);
                                    }
                                }
                                else {
                                    print_r(' Beds not available ');
                                }

                                if($totalDorms < session('dormitory')) {

                                    $availableDorms = session('dormitory') - $totalDorms;

                                    if($request->dorms <= $availableDorms) {
                                        print_r(' Dorms available '.' availableDorms ' . $availableDorms);
                                    }
                                    else {
                                        print_r(' Dorms not available '.' availableDorms ' . $availableDorms);
                                    }
                                }
                                else {
                                    print_r(' Dorms not available ');
                                }
                                print_r(' Date '.$generateBookingDat.' beds: '.session('beds').' totalBeds '. $totalBeds . ' dormitory: '.session('dormitory').' totalDorms '. $totalDorms );
                            }


                            /*print_r(' bookBeds: '.$beds.' BookDorms: '.$dorms).'<br>';
                            print_r(' mschoolBeds: '.$msBeds.' mschoolDorms: '.$msDorms);
                            print_r(' totalBeds: '.$totalBeds.' totalDorms: '.$totalDorms);*/

                            // Alpenrosenhütte (beds:50, dormitory:24)
                            // bookBeds: 41      BookDorms: 42   mschoolBeds: 19   mschoolDorms: 23     Beds not available    Date 2017-09-02  //sat
                            // totalBeds: 60     totalDorms: 65                                         Dorms not available

                            // bookBeds: 27      BookDorms: 20   mschoolBeds: 9    mschoolDorms: 9      Beds available        Date 2017-09-03  //sun
                            // totalBeds: 36     totalDorms: 29  availableBeds 14                       Dorms not available

                            // bookBeds: 27      BookDorms: 20   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-04  //mon
                            // totalBeds: 27     totalDorms: 20  availableBeds 23  availableDorms 4     Dorms available

                            // bookBeds: 12      BookDorms: 20   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-05  //tue
                            // totalBeds: 12     totalDorms: 20  availableBeds 38  availableDorms 4     Dorms available

                            // bookBeds: 12      BookDorms: 20   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-06  //wed
                            // totalBeds: 12     totalDorms: 20  availableBeds 38  availableDorms 4     Dorms available

                            // bookBeds: 12      BookDorms: 20   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-07  //thu
                            // totalBeds: 12     totalDorms: 20  availableBeds 38  availableDorms 4     Dorms available

                            // bookBeds: 37      BookDorms: 44   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-08  //fri
                            // totalBeds: 37     totalDorms: 44  availableBeds 13                       Dorms not available

                            // bookBeds: 37      BookDorms: 44   mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-09  //sat
                            // totalBeds: 37     totalDorms: 44  availableBeds 13                       Dorms not available

                            // bookBeds: 31      BookDorms: 0    mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-10  //sun
                            // totalBeds: 31     totalDorms: 0   availableBeds 19  availableDorms 24    Dorms available

                            // bookBeds: 31      BookDorms: 0    mschoolBeds: 0    mschoolDorms: 0      Beds available        Date 2017-09-11 //mon
                            // totalBeds: 31     totalDorms: 0   availableBeds 19  availableDorms 24    Dorms available

                            // ###################### Alpenrosenhütte (not regular, regular , normal) ###########################
                            // Not regular beds available  availableBeds 2 Not regular dorms available  availableDorms 2
                            // Date 2017-09-02 not_regular_beds: 62 totalBeds 60 not_regular_dorms: 67 totalDorms 65
                            //
                            // sun_beds available  available_sun_beds 2 sun_dorms available  available_sun_dorms 9
                            // Date 2017-09-03 sun_beds: 38 totalBeds 36 sun_dorms: 38 totalDorms 29
                            //
                            // Beds available  availableBeds 23 Dorms available  availableDorms 4
                            // Date 2017-09-04 beds: 50 totalBeds 27 dormitory: 24 totalDorms 20
                            // #################################################

                            // Alpenrosenhütte (not regular)
                            // Not regular beds available  availableBeds 35
                            // Not regular dorms available  availableDorms 20
                            // Date 2017-09-02 not_regular_beds: 95 totalBeds 60 not_regular_dorms: 85 totalDorms 65

                            // Alpenrosenhütte (regular)
                            // sat_beds not available  sat_dorms not available  Date 2017-09-02 sat_beds: 30 totalBeds 60 sat_dorms: 20 totalDorms 65

                            // sun_beds not available  sun_dorms not available  Date 2017-09-03 sun_beds: 25 totalBeds 36 sun_dorms: 15 totalDorms 29

                            // mon beds available  available_mon_beds 3 mon dorms not available  Date 2017-09-04 mon_beds: 30 totalBeds 27 mon_dorms: 20 totalDorms 20

                            // tue_beds available  available_tue_beds 13 tue_dorms not available  Date 2017-09-05 tue_beds: 25 totalBeds 12 tue_dorms: 15 totalDorms 20

                            // wed_beds available  available_wed_beds 3 wed_dorms not available  Date 2017-09-06 wed_beds: 15 totalBeds 12 wed_dorms: 5 totalDorms 20

                            // thu_beds available  available_thu_beds 3 thu_dorms not available  Date 2017-09-07 thu_beds: 15 totalBeds 12 thu_dorms: 5 totalDorms 20

                            // fri_beds not available  fri_dorms not available  Date 2017-09-08 fri_beds: 25 totalBeds 37 fri_dorms: 15 totalDorms 44

                            // sat_beds not available  sat_dorms not available  Date 2017-09-09 sat_beds: 30 totalBeds 37 sat_dorms: 20 totalDorms 44

                            // sun_beds not available  sun_dorms available  available_sun_dorms 15 Date 2017-09-10 sun_beds: 25 totalBeds 31 sun_dorms: 15 totalDorms 0

                            // mon beds not available  mon dorms available  available_mon_dorms 20 Date 2017-09-11 mon_beds: 30 totalBeds 31 mon_dorms: 20 totalDorms 0


                            // Schwarzwasserhutte (beds:40, dormitory:40)
                            // Beds not available Dorms available bookBeds: 72 BookDorms: 24
                            // Beds not available Dorms available bookBeds: 42 BookDorms: 12
                            // Beds not available Dorms available bookBeds: 57 BookDorms: 21
                            // No mschool booking these dates so the total will be same
                        }
                        else {
                            $totalSleeps     = $sleeps + $msSleeps;

                            /* Calculating sleeps of regular and not regular */
                            if ($request->session()->has('regular') || $request->session()->has('not_regular')) {

                                if(session('regular') === 1) {

                                    if($session_mon_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('mon_sleeps')) {

                                            $availableMonSleeps = session('mon_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $availableMonSleeps) {
                                                print_r('Mon sleeps available');
                                            }
                                            else {
                                                print_r('Mon sleeps not available');
                                            }
                                        }
                                        else {
                                            print_r('Mon sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' mon_sleeps: '.session('mon_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableMonSleeps ' . $availableMonSleeps);
                                    }

                                    if($session_tue_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('tue_sleeps')) {

                                            $availableTueSleeps = session('tue_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $availableTueSleeps) {
                                                print_r('Tue sleeps available');
                                            }
                                            else {
                                                print_r('Tue sleeps not available');
                                            }
                                        }
                                        else {
                                            print_r('Tue sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' tue_sleeps: '.session('tue_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableTueSleeps ' . $availableTueSleeps);
                                    }

                                    if($session_wed_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('wed_sleeps')) {

                                            $availableWedSleeps = session('wed_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $availableWedSleeps) {
                                                print_r('Wed sleeps available');
                                            }
                                            else {
                                                print_r('Wed sleeps not available');
                                            }
                                        }
                                        else {
                                            print_r('Wed sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' wed_sleeps: '.session('wed_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableWedSleeps ' . $availableWedSleeps);
                                    }

                                    if($session_thu_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('thu_sleeps')) {

                                            $availableThuSleeps = session('thu_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $availableThuSleeps) {
                                                print_r('Thu sleeps available');
                                            }
                                            else {
                                                print_r('Thu sleeps not available');
                                            }
                                        }
                                        else {
                                            print_r('Tue sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' thu_sleeps: '.session('thu_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableThuSleeps ' . $availableThuSleeps);
                                    }

                                    if($session_fri_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('fri_sleeps')) {

                                            $availableFriSleeps = session('fri_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $availableFriSleeps) {
                                                print_r('Fri sleeps available');
                                            }
                                            else {
                                                print_r('Fri sleeps not available');
                                            }
                                        }
                                        else {
                                            print_r('Fri sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' fri_sleeps: '.session('fri_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableFriSleeps ' . $availableFriSleeps);
                                    }

                                    if($session_sat_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('sat_sleeps')) {

                                            $availableSatSleeps = session('sat_sleeps') - $totalSleeps;
                                            if($request->sleeps <= $availableSatSleeps) {
                                                print_r('Sat sleeps available');
                                            }
                                            else {
                                                print_r('Sat sleeps not available');
                                            }

                                        }
                                        else {
                                            print_r('Sat sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' sat_sleeps: '.session('sat_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableSatSleeps ' . $availableSatSleeps);
                                    }

                                    if($session_sun_day === $generateBookingDay) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('sun_sleeps')) {

                                            $availableSunSleeps = session('sun_sleeps') - $totalSleeps;
                                            if($request->sleeps <= $availableSunSleeps) {
                                                print_r('Sun sleeps available');
                                            }
                                            else {
                                                print_r('Sun sleeps not available');
                                            }

                                        }
                                        else {
                                            print_r('Sat sleeps not available');
                                        }

                                        print_r(' Date '.$generateBookingDat.' sun_sleeps: '.session('sun_sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableSunSleeps ' . $availableSunSleeps);
                                    }
                                }

                                if(session('not_regular') === 1) {
                                    //print_r('not regular');
                                }
                            }

                            /* Calculating sleeps of normal */
                            if(!in_array($generateBookingDat, $regular_dates_array)) {

                                if($totalSleeps < session('sleeps')) {

                                    $availableSleeps = session('sleeps') - $totalSleeps;

                                    if($request->sleeps <= $availableSleeps) {
                                        print_r(' Sleeps available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableSleeps ' . $availableSleeps);
                                    }
                                    else {
                                        print_r(' Sleeps not available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableSleeps ' . $availableSleeps);
                                    }

                                }
                                else {
                                    print_r(' Sleeps not available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' requestSleeps ' . $request->sleeps . ' availableSleeps ' . $availableSleeps);
                                }
                            }

                            /*print_r(' sleeps: '.$sleeps).'<br>';
                            print_r(' mschoolsleeps: '.$msSleeps);
                            print_r(' TotalSleeps: '.$totalSleeps);
                            print_r(' AvailableSleeps: '.$availableSleeps);*/

                            // kempter hutte (Sleeps: 255)
                            // sleeps: 97 mschoolsleeps: 77 TotalSleeps: 174 AvailableSleeps: 81      // 2017-09-02 Sat
                            // sleeps: 94 mschoolsleeps: 64 TotalSleeps: 158 AvailableSleeps: 97      // 2017-09-03 Sun
                            // sleeps: 135 mschoolsleeps: 0 TotalSleeps: 135 AvailableSleeps: 120     // 2017-09-04 Mon
                            // sleeps: 127 mschoolsleeps: 141 TotalSleeps: 268 AvailableSleeps: 120   // 2017-09-05 Tue
                            // sleeps: 143 mschoolsleeps: 37 TotalSleeps: 180 AvailableSleeps: 75     // 2017-09-06 Wed
                            // sleeps: 183 mschoolsleeps: 27 TotalSleeps: 210 AvailableSleeps: 45     // 2017-09-07 Thu
                            // sleeps: 173 mschoolsleeps: 58 TotalSleeps: 231 AvailableSleeps: 24     // 2017-09-08 Fri
                            // sleeps: 150 mschoolsleeps: 64 TotalSleeps: 214 AvailableSleeps: 41     // 2017-09-09 Sat
                            // sleeps: 109 mschoolsleeps: 62 TotalSleeps: 171 AvailableSleeps: 84     // 2017-09-10 Sun
                            // sleeps: 72 mschoolsleeps: 89 TotalSleeps: 161 AvailableSleeps: 94      // 2017-09-11 Mon

                            // kempter hutte (Regular)
                            // Sat sleeps available Date 2017-09-02 sat_sleeps: 216 totalSleeps 174 requestSleeps 1 availableSatSleeps 42
                            // Sun sleeps available Date 2017-09-03 sun_sleeps: 174 totalSleeps 158 requestSleeps 1 availableSunSleeps 16
                            // Mon sleeps available Date 2017-09-04 mon_sleeps: 137 totalSleeps 135 requestSleeps 1 availableMonSleeps 2
                            // Tue sleeps available Date 2017-09-05 tue_sleeps: 270 totalSleeps 268 requestSleeps 1 availableTueSleeps 2
                            // Wed sleeps available Date 2017-09-06 wed_sleeps: 182 totalSleeps 180 requestSleeps 1 availableWedSleeps 2
                            // Thu sleeps available Date 2017-09-07 thu_sleeps: 212 totalSleeps 210 requestSleeps 1 availableThuSleeps 2
                            // Fri sleeps available Date 2017-09-08 fri_sleeps: 233 totalSleeps 231 requestSleeps 1 availableFriSleeps 2
                            // Sat sleeps available Date 2017-09-09 sat_sleeps: 216 totalSleeps 214 requestSleeps 1 availableSatSleeps 2
                            // Sun sleeps available Date 2017-09-10 sun_sleeps: 174 totalSleeps 171 requestSleeps 1 availableSunSleeps 3
                            // Mon sleeps not available Date 2017-09-11 mon_sleeps: 137 totalSleeps 161 requestSleeps 1 availableMonSleeps 2
                        }
                    }
                    else {
                        $limit = 'Quota exceeded';
                    }
                }
            }
        }

        return response()->json(['disableDates' => $disableDates, 'limit' => $limit]);
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
