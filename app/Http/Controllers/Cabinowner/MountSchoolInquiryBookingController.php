<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MountSchoolInquiryBookingRequest;
use App\Cabin;
use App\MountSchoolBooking;
use App\Userlist;
use App\PrivateMessage;
use Auth;
use Mail;

class MountSchoolInquiryBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($bookId = null, $senderId = null)
    {
        if($bookId != null && $senderId != null) {
            if ($senderId == 'new') {
                MountSchoolBooking::where('_id', new \MongoDB\BSON\ObjectID($bookId))
                    ->update(['read' => 1]); //1 = read, 0 = unread
            }
            else {
                PrivateMessage::where('booking_id', new \MongoDB\BSON\ObjectID($bookId))
                    ->where('receiver_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
                    ->where('sender_id', new \MongoDB\BSON\ObjectID($senderId))
                    ->update(['read' => 1]); //1 = read, 0 = unread
            }
            return view('cabinowner.mountSchoolInquiry', ['bookId' => $bookId]);
        }
        return view('cabinowner.mountSchoolInquiry');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\MountSchoolInquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(MountSchoolInquiryBookingRequest $request)
    {
        $params        = $request->all();

        $columns       = array(
            1 => 'invoice_number',
            2 => 'usrLastname',
            3 => 'usrFirstname',
            4 => 'usrEmail',
            5 => 'check_in',
            6 => 'reserve_to',
            7 => 'beds',
            8 => 'dormitory',
            9 => 'sleeps',
            10 => 'answered',
            11 => 'inquirystatus'
        );

        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin)
            {
                $cabin_name = $cabin->name;

                if($request->parameterId) {
                    $totalData  = MountSchoolBooking::where('is_delete', 0)
                        ->where('cabin_name', $cabin_name)
                        ->where('typeofbooking', 1)
                        ->where('status', "7")
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->parameterId))
                        ->count();

                    $q          = MountSchoolBooking::where('is_delete', 0)
                        ->where('typeofbooking', 1)
                        ->where('status', "7")
                        ->where('cabin_name', $cabin_name)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->parameterId));
                }
                else {
                    $totalData  = MountSchoolBooking::where('is_delete', 0)
                        ->where('cabin_name', $cabin_name)
                        ->where('typeofbooking', 1)
                        ->where('status', "7")
                        ->count();

                    $q          = MountSchoolBooking::where('is_delete', 0)
                        ->where('typeofbooking', 1)
                        ->where('status', "7")
                        ->where('cabin_name', $cabin_name);
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
                                $query->where('user_id', new \MongoDB\BSON\ObjectID($user->_id));
                            });

                            $totalFiltered = $q->where(function($query) use ($user) {
                                $query->where('user_id', new \MongoDB\BSON\ObjectID($user->_id));
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

                    $q->whereBetween('check_in', [$dateBegin, $dateEnd]);

                    $totalFiltered = $q->whereBetween('check_in', [$dateBegin, $dateEnd])
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
                        ->skip($start)
                        ->take($limit)
                        ->get();

                    if(count($users) > 0) {
                        foreach ($users as $user) {
                            $q->where(function($query) use ($user) {
                                $query->where('user_id', new \MongoDB\BSON\ObjectID($user->_id));
                            });

                            $totalFiltered = $q->where(function($query) use ($user) {
                                $query->where('user_id', new \MongoDB\BSON\ObjectID($user->_id));
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
                        /* For user details */
                        $users = Userlist::where('_id', $booking->user_id)
                            ->get();
                        foreach ($users as $user){
                            $bookings[$key]['userId']              = $user->_id;
                            $bookings[$key]['usrEmail']            = $user->usrEmail;
                            $bookings[$key]['usrFirstname']        = $user->usrFirstname;
                            $bookings[$key]['usrLastname']         = $user->usrLastname;
                            $bookings[$key]['usrCity']             = $user->usrCity;
                            $bookings[$key]['usrAddress']          = $user->usrAddress;
                            $bookings[$key]['usrTelephone']        = $user->usrTelephone;
                            $bookings[$key]['usrMobile']           = $user->usrMobile;
                            $bookings[$key]['usrZip']              = $user->usrZip;
                        }

                        /* Checking check_in, reserve_to and booking date fields are available or not begin*/
                        if(!$booking->check_in){
                            $check_in = $noData;
                        }
                        else {
                            $check_in = ($booking->check_in)->format('d.m.y');
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

                        /* Functionality for private message */
                        $private_messages = [];
                        $senderTxt        = '';
                        $senderLname      = '';
                        $senderFname      = '';
                        $receiverTxt      = '';
                        $receiverLname    = '';
                        $receiverFname    = '';
                        $senderMsg    = '';
                        $receiverMsg    = '';

                        $readPrivateMessages = PrivateMessage::where('booking_id', new \MongoDB\BSON\ObjectID($booking->_id))
                            ->whereIn('receiver_id', [new \MongoDB\BSON\ObjectID(Auth::user()->_id), new \MongoDB\BSON\ObjectID($bookings[$key]['userId'])])
                            ->whereIn('sender_id', [new \MongoDB\BSON\ObjectID(Auth::user()->_id), new \MongoDB\BSON\ObjectID($bookings[$key]['userId'])])
                            ->orderBy('created_at')
                            ->get();

                        if(count($readPrivateMessages) > 0){
                            foreach ($readPrivateMessages as $readPrivateMessage){
                                if($readPrivateMessage->sender_id == $bookings[$key]['userId'] && $readPrivateMessage->receiver_id == Auth::user()->_id) {
                                    $senderTxt       = $readPrivateMessage->text;
                                    $senderCreatedAt = ($readPrivateMessage->created_at)->format('d M H:i:s A');
                                    $senderLname     = $readPrivateMessage->sender->usrLastname;
                                    $senderFname     = $readPrivateMessage->sender->usrFirstname;
                                    $senderMsg .= '<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'.$senderFname.' '.$senderLname.'</span><span class="direct-chat-timestamp pull-right">'.$senderCreatedAt.'</span></div><i class="menu-icon bg-light-blue direct-chat-img text-center" style="padding: 9px;">G</i><div class="direct-chat-text">'.$senderTxt.'</div></div>';
                                }

                                if($readPrivateMessage->sender_id == Auth::user()->_id && $readPrivateMessage->receiver_id == $bookings[$key]['userId']) {
                                    $receiverTxt       = $readPrivateMessage->text;
                                    $receiverCreatedAt = ($readPrivateMessage->created_at)->format('d M H:i:s A');
                                    $receiverLname     = $readPrivateMessage->sender->usrLastname;
                                    $receiverFname     = $readPrivateMessage->sender->usrFirstname;
                                    $senderMsg .= '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'.$receiverFname.' '.$receiverLname.'</span><span class="direct-chat-timestamp pull-left">'.$receiverCreatedAt.'</span></div><i class="menu-icon label-default direct-chat-img text-center" style="padding: 9px;">HW</i><div class="direct-chat-text">'.$receiverTxt.'</div></div>';
                                }
                            }
                            $private_messages = '<i class="fa fa-fw fa-comments" data-toggle="modal" data-target="#msgModal_'.$booking->_id.'"></i><div class="modal fade" id="msgModal_'.$booking->_id.'" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="col-md-12"><div class="box box-primary direct-chat direct-chat-warning"><div class="box-header with-border"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 class="box-title">Chat</h3><div class="msgResponse"></div></div><div class="msgHide"><div class="box-body"><div class="direct-chat-messages">'.$senderMsg.'</div></div><div class="box-footer"><div class="input-group margin col-md-12"><input type="hidden" name="sender" id="sender_'.$booking->_id.'" class="form-control" value="'.Auth::user()->_id.'"><input type="hidden" name="receiver" id="receiver_'.$booking->_id.'" class="form-control"  value="'.$bookings[$key]['userId'].'"><input type="hidden" name="bookingId" id="bookingId_'.$booking->_id.'" class="form-control" value="'.$booking->_id.'"><input type="hidden" name="subject" id="subject_'.$booking->_id.'" class="form-control" value="'.$booking->invoice_number.'"><input type="hidden" name="usrEmail" id="usrEmail_'.$booking->_id.'" class="form-control" value="'.$user_email.'"><input type="hidden" name="cabinName" id="cabinName_'.$booking->_id.'" class="form-control" value="'.$booking->cabin_name.'"><input type="text" name="message" id="message_'.$booking->_id.'" placeholder="Bitte Antwort hier eingeben..." class="form-control" autocomplete="off"><span class="input-group-btn"><button type="button" class="btn btn-info btn-flat msgSend" data-book="'.$booking->_id.'" data-loading-text="Sending..." autocomplete="off">'.__("inquiry.sendButton").'</button></span></div></div></div></div></div></div></div></div>';
                        }
                        else{
                            $private_messages  =  '<span class="label label-default">'.__("inquiry.notAsked").'</span>';
                        }

                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" /><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("inquiry.moreDetails").'</h4><div class="response"></div></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.address").'</h4><p class="list-group-item-text">'.$usr_address.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.city").'</h4><p class="list-group-item-text">'.$usr_city.'</p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.usrZip").'</h4><p class="list-group-item-text">'.$usr_zip.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.telephone").'</h4><p class="list-group-item-text">'.$usr_telephone.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("inquiry.mobile").'</h4><p class="list-group-item-text">'.$usr_mobile.'</p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("inquiry.close").'</button></div></div></div></div>';
                        $nestedData['invoice_number']          = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                        $nestedData['usrLastname']             = $last_name;
                        $nestedData['usrFirstname']            = $first_name;
                        $nestedData['usrEmail']                = $user_email;
                        $nestedData['check_in']                = $check_in;
                        $nestedData['reserve_to']              = $reserve_to;
                        $nestedData['beds']                    = $beds;
                        $nestedData['dormitory']               = $dormitory;
                        $nestedData['sleeps']                  = $sleeps;
                        $nestedData['answered']                = $private_messages;
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
     * send message to user.
     *
     * @param  \App\Http\Requests\MountSchoolInquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(MountSchoolInquiryBookingRequest $request)
    {
        $privateMessage              = new PrivateMessage;
        $privateMessage->sender_id   = new \MongoDB\BSON\ObjectID($request->sender);
        $privateMessage->receiver_id = new \MongoDB\BSON\ObjectID($request->receiver);
        $privateMessage->booking_id  = new \MongoDB\BSON\ObjectID($request->bookingId);
        $privateMessage->subject     = $request->subject;
        $privateMessage->text        = $request->message;
        $privateMessage->read        = 0;
        $privateMessage->save();

        /* Functionality to send message to guest begin*/
        $comment                = $request->message;
        Mail::send('emails.sendMessageToGuest', ['comment' => $comment, 'subject' => 'Nachricht von', 'buttonLabel' => 'Anmelden', 'cabinName' => $request->cabinName, 'email' => $request->usrEmail], function ($message) use ($request) {
            $message->from('no-reply@huetten-holiday.de', 'No-Reply');
            $message->to($request->usrEmail)->subject('Nachricht von '.$request->cabinName);
        });
        /* Functionality to send message to guest end */

        return response()->json(['msgStatus' => __("inquiry.msgStatus")], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     *  @param  \App\Http\Requests\MountSchoolInquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function approveStatus(MountSchoolInquiryBookingRequest $request)
    {
        $inquiry                = MountSchoolBooking::findOrFail($request->data);
        $inquiry->inquirystatus = 1; //0 = waiting, 1 = Approved, 2 = Rejected
        $inquiry->status        = '5';
        $inquiry->save();

        $user                   = Userlist::where('_id', $inquiry->user)
            ->first();
        $user_email             = $user->usrEmail;

        /* Functionality to send inquiry status approval message to guest begin*/
        $comment                = 'Sie wandern richtung '.$inquiry->cabin_name.'! Ihre Anfrage bezüglich der Übernachtung von: '.($inquiry->check_in)->format('d.m.y').', bis: '.($inquiry->reserve_to)->format('d.m.y').' mit insgesamt: '.$inquiry->sleeps.' Personen wurde akzeptiert. Um die Buchung fix und damit auch verpflichtend zu machen, melden Sie sich bitte Online an und leisten Sie die fällige Anzahlung.';

        Mail::send('emails.inquiryStatusMessage', ['comment' => $comment, 'cabinName' => $inquiry->cabin_name, 'buttonLabel' => 'Anzahlung leisten', 'subject' => 'Info von', 'email' => $user_email], function ($message) use ($user_email, $inquiry) {
            $message->from('no-reply@huetten-holiday.de', 'No-Reply');
            $message->to($user_email)->subject('Info von '.$inquiry->cabin_name);
        });
        /* Functionality to send inquiry status approval message to guest end */

        return response()->json(['statusInquiry' => __("inquiry.inquiryStatusApproved"), 'inquiryStatusApprovedSec' => __("inquiry.inquiryStatusApprovedSec"), 'dataId' => $request->data], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     *  @param  \App\Http\Requests\MountSchoolInquiryBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function rejectStatus(MountSchoolInquiryBookingRequest $request)
    {
        $inquiry                = MountSchoolBooking::findOrFail($request->data);
        $inquiry->inquirystatus = 2; //0 = waiting, 1 = Approved, 2 = Rejected
        $inquiry->save();

        $user                   = Userlist::where('_id', $inquiry->user_id)
            ->first();
        $user_email             = $user->usrEmail;

        /* Functionality to send inquiry status approval message to guest begin*/
        $comment                = 'Ihre Anfrage bezüglich der Übernachtung von: '.($inquiry->check_in)->format('d.m.y').', bis: '.($inquiry->reserve_to)->format('d.m.y').' mit insgesamt: '.$inquiry->sleeps.' Personen wurde leider nicht akzeptiert. Vielleicht kommt eine andere Hütte oder ein anderes Datum in Frage? Finden Sie bei uns die passende Hütte!';

        Mail::send('emails.inquiryStatusMessage', ['comment' => $comment, 'cabinName' => $inquiry->cabin_name, 'buttonLabel' => 'Hütten buchen', 'subject' => 'Info von', 'email' => $user_email], function ($message) use ($user_email, $inquiry) {
            $message->from('no-reply@huetten-holiday.de', 'No-Reply');
            $message->to($user_email)->subject('Info von '.$inquiry->cabin_name);
        });
        /* Functionality to send inquiry status approval message to guest end */
        return response()->json(['statusInquiry' => __("inquiry.inquiryStatusRejected")], 201);
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
