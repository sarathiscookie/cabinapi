<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Userlist;
use App\Tempuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FaultyPayment;
use App\Mail\SuccessPaymentAttachment;
use App\Mail\SendInvoice;
use Yajra\Datatables\Facades\Datatables;
use Auth;
use DateTime;

class BookingController extends Controller
{
    /**
     * Display data table page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId = null, $count = null)
    {
        if($userId != null && $count != null){
            return view('backend.bookings', ['userID' => $userId, 'count' => $count]);
        }
        return view('backend.bookings');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params = $request->all();

        $columns = array(
            1 => 'invoice_number',
            2 => 'usrEmail',
            3 => 'checkin_from',
            4 => 'reserve_to',
            5 => 'beds',
            6 => 'dormitory',
            7 => 'sleeps',
            8 => 'status',
            9 => 'payment_status',
            10 => 'payment_type',
            11 => 'total_prepayment_amount',
            12 => 'txid'
        );

        if($request->parameterId)
        {
            $totalData = Booking::where('is_delete', 0)
                ->where('user', new \MongoDB\BSON\ObjectID($request->parameterId))
                ->count();
            $q         = Booking::where('is_delete', 0)
                ->where('user', new \MongoDB\BSON\ObjectID($request->parameterId));
        }
        else {
            $totalData = Booking::where('is_delete', 0)->count();
            $q         = Booking::where('is_delete', 0);
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
            $users    = Userlist::where('is_delete', 0)
                ->where(function($query) use ($search) {
                    $query->where('usrEmail', 'like', "%{$search}%");
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
                    $query->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('payment_type', 'like', "%{$search}%")
                        ->orWhere('txid', 'like', "%{$search}%");
                });

                $totalFiltered = $q->where(function($query) use ($search) {
                    $query->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('payment_type', 'like', "%{$search}%")
                        ->orWhere('txid', 'like', "%{$search}%");
                })
                    ->count();
            }

        }

        /* Date range func and cabin name filter func begin */
        if($request->input('is_date_search') == 'yes')
        {
            //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
            $checkin_from           = explode("-", $request->input('daterange'));
            $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
            $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);

            if($request->cabin == 'allCabins'){
                $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                    $query->whereBetween('checkin_from', [$dateBegin, $dateEnd]);
                });

                $totalFiltered          =  $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                    $query->whereBetween('checkin_from', [$dateBegin, $dateEnd]);
                })
                    ->count();
            }
            else {
                $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                    $query->whereBetween('checkin_from', [$dateBegin, $dateEnd])
                        ->where('cabinname', $request->cabin);
                });

                $totalFiltered          =  $q->where(function($query) use ($dateBegin,$dateEnd,$request) {
                    $query->whereBetween('checkin_from', [$dateBegin, $dateEnd])
                        ->where('cabinname', $request->cabin);
                })
                    ->count();
            }
        }
        /* tfoot search functionality for booking number, email, payment type, txid begin */

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

        if( !empty($params['columns'][8]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][8]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][8]['search']['value']}");
            })
                ->count();
        }

        if( isset($params['columns'][9]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('payment_status', "{$params['columns'][9]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('payment_status', "{$params['columns'][9]['search']['value']}");
            })
                ->count();
        }

        if( !empty($params['columns'][10]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('payment_type', 'like', "%{$params['columns'][10]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('payment_type', 'like', "%{$params['columns'][10]['search']['value']}%");
            })
                ->count();
        }

        if( !empty($params['columns'][12]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('txid', 'like', "%{$params['columns'][12]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('txid', 'like', "%{$params['columns'][12]['search']['value']}%");
            })
                ->count();
        }


        if( !empty($params['columns'][2]['search']['value']) ) {
            $users     = Userlist::select('_id', 'usrEmail')
                ->where('is_delete', 0)
                ->where(function($query) use ($params) {
                    $query->where('usrEmail', 'like', "%{$params['columns'][2]['search']['value']}%");
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
        }
        /* tfoot search functionality for booking number, email, payment type, txid end */

        $bookings      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        $data   = array();
        $noData = '<span class="label label-default">'.__("admin.noResult").'</span>';
        if(!empty($bookings))
        {
            foreach ($bookings as $key=> $booking)
            {
                /* Condition for checking who booked bookings. If a booking collection has temp_user_id then show notification (Booked by cabin owner) otherwise user email. begin*/
                $bookings[$key]['usrEmail'] = $noData;
                if($booking->temp_user_id != ""){
                    $tempUsers = Tempuser::select('usrFirstname', 'usrLastname', 'usrEmail')
                        ->where('_id', $booking->temp_user_id)
                        ->get();
                    foreach ($tempUsers as $tempUser){
                        $usrEmail = 'cabinowner';
                        $bookings[$key]['usrEmail'] = $usrEmail;
                    }
                }
                else{
                    $users = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrAddress', 'usrTelephone', 'usrMobile')
                        ->where('_id', $booking->user)
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
                /* Condition for checking who booked bookings end*/

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

                /* Condition for payment status begin */
                if ($booking->payment_status == '0') {
                    $paymentStatusLabel = '<span class="label label-danger">'.__("admin.paymentStatusFailed").'</span>';
                }
                else if($booking->payment_status == '1') {
                    $paymentStatusLabel = '<span class="label label-success">'.__("admin.paymentStatusDone").'</span>';
                }
                else if($booking->payment_status == '2') {
                    $paymentStatusLabel = '<span class="label label-info">'.__("admin.paymentStatusCabinOwner").'</span>'; //2- payment status for cabin owner
                }
                else if ($booking->payment_status == '3') {
                    $paymentStatusLabel = '<span class="label label-warning">'.__("admin.waitingForPayment").'</span>'; //3- prepayment
                }
                else {
                    $paymentStatusLabel = $noData;
                }
                /* Condition for payment status end */

                /* Checking checkin_from, reserve_to and booking date fields are available or not */
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

                /* Condition for user is cabin owner or not  */
                if($bookings[$key]['usrEmail'] == 'cabinowner') {
                    $bookedBy        = '<span class="label label-info">'.__('admin.bookedByCabinOwner').'</span>';
                    $sendVoucherHtml = '<span class="label label-warning">'.__('admin.bookedByCabinOwner').'</span>';
                    $checkbox        = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" disabled/>';
                }
                else {
                    $bookedBy        = $bookings[$key]['usrEmail'];
                    $sendVoucherHtml = '<button class="btn btn-primary btn-sm sendInvoice" data-loading-text="'.__('admin.sendingProcess').'" autocomplete="off"><i class="fa fa-envelope"></i> Send</button>';
                    $checkbox        = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                }

                $nestedData['hash']                    = $checkbox;
                $nestedData['invoice_number']          = '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                $nestedData['usrEmail']                = $bookedBy;
                $nestedData['checkin_from']            = $checkin_from;
                $nestedData['reserve_to']              = $reserve_to;
                $nestedData['beds']                    = $booking->beds;
                $nestedData['dormitory']               = $booking->dormitory;
                $nestedData['sleeps']                  = $booking->sleeps;
                $nestedData['status']                  = $bookingStatusLabel;
                $nestedData['payment_status']          = $paymentStatusLabel;
                $nestedData['payment_type']            = $booking->payment_type;
                $nestedData['total_prepayment_amount'] = $booking->total_prepayment_amount;
                $nestedData['txid']                    = $booking->txid;
                $nestedData['payment_status_update']   = '<input class="pay_status_update"  type="hidden" name="pay_status_update" value="'.$booking->_id.'" data-bookid="'.$booking->_id.'" /><button class="btn btn-primary paymentStatusUpdate" data-loading-text="Updating..." autocomplete="off" data-toggle="tooltip" data-placement="top" title="'.__("admin.paymentStatusUpdateTooltip").'"><i class="fa fa-euro"></i></button>';
                $nestedData['action']                  = '<a href="/bookings/'.$booking->_id.'" class="btn btn-xs btn-danger deleteEvent" data-id="'.$booking->_id.'"><i class="glyphicon glyphicon-trash"></i> '.__("admin.deleteButton").'</a><div class="modal fade" id="bookingModal_'.$booking->_id.'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("admin.moreDetails").'</h4></div><div class="alert alert-success alert-dismissible alert-invoice" style="display: none;"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '.__("admin.wellDone").'</h4>'.__("admin.sendVoucherSuccessResponse").'</div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.cabinName").'</h4><p class="list-group-item-text">'.$booking->cabinname.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.referenceNumber").'</h4><p class="list-group-item-text">'.$booking->reference_no.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.clubMember").'</h4><p class="list-group-item-text">'.$booking->clubmember.'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.bookingDate").'</h4><p class="list-group-item-text">'.$bookingdate.'</p></li><li class="list-group-item" data-invoice="'.$booking->_id.'"><h4 class="list-group-item-heading">'.__("admin.voucher").'</h4>'.$sendVoucherHtml.'</li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.firstName").'</h4><p class="list-group-item-text">'.$bookings[$key]['usrFirstname'].'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.lastName").'</h4><p class="list-group-item-text">'.$bookings[$key]['usrLastname'].'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.address").'</h4><p class="list-group-item-text">'.$bookings[$key]['usrAddress'].'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.telephone").'</h4><p class="list-group-item-text">'.$bookings[$key]['usrTelephone'].'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("admin.mobile").'</h4><p class="list-group-item-text">'.$bookings[$key]['usrMobile'].'</p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
                $data[]                                = $nestedData;
            }
        }

        $json_data = array(
            'draw'            => (int)$params['draw'],
            'recordsTotal'    => (int)$totalData,
            'recordsFiltered' => (int)$totalFiltered,
            'data'            => $data
        );

        return response()->json($json_data);
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
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Booking::findOrFail($id);

        return response()->json(['booking' => $booking], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach ($request->bookingId as $id) {
            $bookingDetails                 = Booking::find($id);
            $bookingDetails->status_comment = 'Payment updated via backend';
            $bookingDetails->status         = "1";
            $bookingDetails->payment_status = "1";
            $bookingDetails->sent_email     = 1;
            $bookingDetails->status_admin   = Auth::user()->_id;
            $bookingDetails->save();

            /* Functionality to send attachment email about payment success begin */
            Mail::send(new SuccessPaymentAttachment($bookingDetails));
            /* Functionality to send attachment email about payment success end */
        }

        $message                            = __('admin.paymentStatusSuccessResponse');
        return response()->json(['message' => $message], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateIndividual(Request $request)
    {
        $bookingDetails                 = Booking::find($request->bookingId);
        $bookingDetails->status_comment = 'Payment updated via backend';
        $bookingDetails->status         = "1";
        $bookingDetails->payment_status = "1";
        $bookingDetails->sent_email     = 1;
        $bookingDetails->status_admin   = Auth::user()->_id;
        $bookingDetails->save();

        /* Functionality to send attachment email about payment success begin */
        Mail::send(new SuccessPaymentAttachment($bookingDetails));
        /* Functionality to send attachment email about payment success end */

        $message                        = __('admin.paymentStatusSuccessResponse');
        return response()->json(['message' => $message], 201);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function sendInvoice($id)
    {
        $bookingDetails                 = Booking::find($id);

        /* Functionality to send invoice begin */
        Mail::send(new SendInvoice($bookingDetails));
        /* Functionality to send invoice end */
        return response()->json(['message' => __('admin.sendVoucherSuccessResponse')], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking            = Booking::findOrFail($id);
        $booking->is_delete = 1;
        $booking->save();

        return response()->json(['message' => __('admin.bookingDeleteSuccessResponse')], 201);
    }
}
