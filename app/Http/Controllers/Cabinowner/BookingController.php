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
use App\PrivateMessage;
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
        $params  = $request->all();

        $columns = array(
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
            12 => 'answered',
            13 => 'messages',
            14 => 'notes',
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
                        ->whereIn('status', ['1', '2', '3', '4', '5'])
                        ->count();
                    $q         = Booking::where('is_delete', 0)
                        ->whereIn('status', ['1', '2', '3', '4', '5'])
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
                        /* Condition to check who booked bookings. If a booking collection has temp_user_id then show notification (Booked by cabin owner) otherwise user email. */
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

                                /* 1) Condition to check who booked bookings. If usrlId is 3 then show notification (Booked by cabin owner)
                                   2) If usrlId is 3 then this booking was done by cabin owner. So show cancel button */
                                if($user->usrlId === 3) {
                                    $bookings[$key]['bookedBy']        = '<span class="badge" data-toggle="tooltip" data-placement="top" title="'.__('cabinowner.bookedByCabinOwner').'">BCO</span>';
                                    $bookings[$key]['cancel']          = '<div class="row cancelDiv"><div class="col-md-12"><ul class="list-group"><li class="list-group-item"><label>'.__("cabinowner.action").'</label> <button type="button" class="btn btn-danger btn-sm cancel"><span data-cancel="'.$booking->_id.'" class="spanCancel"></span>Stornieren</button></li></ul></div></div>';
                                }
                                else {
                                    $bookings[$key]['bookedBy']        = '';
                                    $bookings[$key]['cancel']          = '';
                                }

                                $bookings[$key]['usrEmail']            = $usrEmail;
                                $bookings[$key]['usrFirstname']        = $user->usrFirstname;
                                $bookings[$key]['usrLastname']         = $user->usrLastname;
                                $bookings[$key]['usrCity']             = $user->usrCity;
                                $bookings[$key]['usrAddress']          = $user->usrAddress;
                                $bookings[$key]['usrTelephone']        = $user->usrTelephone;
                                $bookings[$key]['usrMobile']           = $user->usrMobile;
                                $bookings[$key]['usrZip']              = $user->usrZip;

                            }
                        }

                        /* Condition for booking status begin */
                        if($booking->status == '1') {
                            $bookingStatusLabel = '<span class="label label-success">'.__("cabinowner.bookingFix").'</span>';
                        }
                        elseif ($booking->status == '2') {
                            $bookingStatusLabel = '<span class="label label-danger">'.__("cabinowner.cancelled").'</span>';
                        }
                        elseif ($booking->status == '3') {
                            $bookingStatusLabel = '<span class="label label-primary">'.__("cabinowner.completed").'</span>';
                        }
                        elseif ($booking->status == '4') {
                            $bookingStatusLabel = '<span class="label label-info">'.__("cabinowner.request").'</span>';
                        }
                        elseif ($booking->status == '5') {
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
                                $messageStatus = '<a class="btn bg-purple-light" data-toggle="modal" data-target="#messageModal_'.$booking->_id.'"><i class="fa fa-envelope"></i></a><div class="modal fade" id="messageModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.sendMessageHead").'</h4></div><div class="alert alert-success alert-message" style="display: none;"><h4><i class="icon fa fa-check"></i> '.__("cabinowner.wellDone").' </h4>'.__("cabinowner.sendMessageSuccessResponse").'</div><div class="alert alert-danger alert-message-failed" style="display: none;">'.__("cabinowner.enterYourMsgAlert").' </div><div class="modal-body"><textarea class="form-control" style="min-width: 100%;" rows="3" placeholder="'.__("cabinowner.enterYourMsg").'" id="messageTxt_'.$booking->_id.'"></textarea></div><div class="modal-footer"><input class="message_status_update"  type="hidden" name="message_text" value="'.$booking->_id.'" data-id="'.$booking->_id.'" /><button type="button" data-loading-text="'.__("cabinowner.sendingProcess").'" autocomplete="off" class="btn bg-purple messageStatusUpdate">'.__("cabinowner.sendButton").'</button></div></div></div></div>';
                            }

                        }
                        else {
                            $invoiceNumber_comment = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';

                            /*Condition to check cabin owner answered*/
                            $messageStatus = '<span class="label label-default">'.__("cabinowner.notAsked").'</span>';
                        }

                        // Get messages only if booking has inquiry
                        if ($booking->typeofbooking == 1) {
                            $inquiryMessages = PrivateMessage::where('booking_id', new \MongoDB\BSON\ObjectID($booking->_id))
                            ->orderBy('created_at')
                            ->get();

                            if (count($inquiryMessages) > 0) {
                                // Build messages for displaying inside modal
                                $msgContent = '';
                                foreach ($inquiryMessages as $message) {
                                    // Message Sender
                                    if ($message->sender_id != Auth::id()) {
                                        $senderTxt       = $message->text;
                                        $senderCreatedAt = ($message->created_at)->format('d M H:i:s A');
                                        $senderLname     = $message->sender->usrLastname;
                                        $senderFname     = $message->sender->usrFirstname;

                                        $msgContent .= '<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'.$senderFname.' '.$senderLname.'</span><span class="direct-chat-timestamp pull-right">'.$senderCreatedAt.'</span></div><i class="menu-icon bg-light-blue direct-chat-img text-center" style="padding: 9px;">G</i><div class="direct-chat-text">'.$senderTxt.'</div></div>';
                                    }
                                    // Message Receiver
                                    else {
                                        $receiverTxt       = $message->text;
                                        $receiverCreatedAt = ($message->created_at)->format('d M H:i:s A');
                                        $receiverLname     = $message->sender->usrLastname;
                                        $receiverFname     = $message->sender->usrFirstname;

                                        // Message content
                                        $msgContent .= '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'.$receiverFname.' '.$receiverLname.'</span><span class="direct-chat-timestamp pull-left">'.$receiverCreatedAt.'</span></div><i class="menu-icon label-default direct-chat-img text-center" style="padding: 9px;">HW</i><div class="direct-chat-text">'.$receiverTxt.'</div></div>';
                                    }
                                }

                                // Inquiry messages toggle button
                                if ($message->text) {
                                    $inq_msg_column = '<i class="fa fa-fw fa-comments" data-toggle="modal" data-target="#msgModal_'.$booking->_id.'"></i><div class="modal fade" id="msgModal_'.$booking->_id.'" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="col-md-12"><div class="box box-primary direct-chat direct-chat-warning"><div class="box-header with-border"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 class="box-title">Messages</h3><div class="msgResponse"></div></div><div class="msgHide"><div class="box-body"><div class="direct-chat-messages">'.$msgContent.'</div></div><div class="box-footer"><div class="input-group margin col-md-12"><input type="hidden" name="sender" id="sender_'.$booking->_id.'" class="form-control" value="'.Auth::user()->_id.'"><input type="hidden" name="receiver" id="receiver_'.$booking->_id.'" class="form-control"  value="'.$booking->userId.'"><input type="hidden" name="bookingId" id="bookingId_'.$booking->_id.'" class="form-control" value="'.$booking->_id.'"><input type="hidden" name="subject" id="subject_'.$booking->_id.'" class="form-control" value="'.$booking->invoice_number.'"><input type="hidden" name="usrEmail" id="usrEmail_'.$booking->_id.'" class="form-control" value="'.$booking->usrEmail.'"><input type="hidden" name="cabinName" id="cabinName_'.$booking->_id.'" class="form-control" value="'.$booking->cabinname.'"></div></div></div></div></div></div></div></div>';
                                } else {
                                    $inq_msg_column = '----';
                                }
                            } else {
                                $inq_msg_column = '----';
                            }
                        } else {
                            $inq_msg_column = '----';
                        }

                        // Get notes for booking
                        if ($booking->notes) {
                            $notes_column = '<a class="btn bg-purple-light" data-toggle="modal" data-target="#editNoteModal_'.$booking->_id.'"><i class="fa fa-sticky-note"></i></a><div class="modal fade" id="editNoteModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="editNoteModallLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.editNoteHead").'</h4></div><div class="alert alert-success alert-message" style="display: none;"><h4><i class="icon fa fa-check"></i> '.__("cabinowner.wellDone").' </h4>'.__("cabinowner.storeNoteSuccessResponse").'</div><div class="alert alert-danger alert-message-failed" style="display: none;">'.__("cabinowner.enterYourNoteAlert").' </div><div class="modal-body"><textarea class="form-control" style="min-width: 100%;" rows="3" id="note_'.$booking->_id.'">'.$booking->notes.'</textarea></div><div class="modal-footer"><input class="store_note"  type="hidden" name="message_text" value="'.$booking->_id.'" data-id="'.$booking->_id.'" /><button type="button" data-loading-text="'.__("cabinowner.sendingProcess").'" autocomplete="off" class="btn bg-purple storeNoteButton">'.__("cabinowner.saveButton").'</button></div></div></div></div>';
                        } else {
                            $notes_column = '<a class="btn bg-primary" data-toggle="modal" data-target="#storeNoteModal_'.$booking->_id.'"><i class="fa fa-plus"></i></a><div class="modal fade" id="storeNoteModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="storeNoteModallLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.storeNoteHead").'</h4></div><div class="alert alert-success alert-message" style="display: none;"><h4><i class="icon fa fa-check"></i> '.__("cabinowner.wellDone").' </h4>'.__("cabinowner.storeNoteSuccessResponse").'</div><div class="alert alert-danger alert-message-failed" style="display: none;">'.__("cabinowner.enterYourNoteAlert").' </div><div class="modal-body"><textarea class="form-control" style="min-width: 100%;" rows="3" placeholder="'.__("cabinowner.enterYourNote").'" id="note_'.$booking->_id.'"></textarea></div><div class="modal-footer"><input class="store_note"  type="hidden" name="note_text" value="'.$booking->_id.'" data-id="'.$booking->_id.'" /><button type="button" data-loading-text="'.__("cabinowner.sendingProcess").'" autocomplete="off" class="btn bg-purple storeNoteButton">'.__("cabinowner.saveButton").'</button></div></div></div></div>';
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


                        $nestedData['hash']                    = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" /><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabinowner.moreDetails").'</h4><div class="response"></div></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.address").'</h4><p class="list-group-item-text">'.$usr_address.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.city").'</h4><p class="list-group-item-text">'.$usr_city.'</p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.usrZip").'</h4><p class="list-group-item-text">'.$usr_zip.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.telephone").'</h4><p class="list-group-item-text">'.$usr_telephone.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabinowner.mobile").'</h4><p class="list-group-item-text">'.$usr_mobile.'</p></li></ul></div></div>'.$bookings[$key]['cancel'].'</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("cabinownerBooking.close").'</button></div></div></div></div>';
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
                        $nestedData['messages']                = $inq_msg_column;
                        $nestedData['notes']                   = $notes_column;
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
     * Save or update a note on a booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNote(Request $request)
    {
        $resource = json_decode($request->data, true);

        $booking = Booking::where('_id', $resource['id'])->first();

        $booking->notes = $resource['note'];
        $booking->save();

        return response()->json(['note' => $resource['note']], 201);
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
