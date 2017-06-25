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
use DB;

class BookingController extends Controller
{
    /**
     * Display data table page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$bookings = Booking::with('searchuser')
            ->limit(5)
            ->get();
        return $bookings;*/
        return view('backend.bookings');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $columns       = array('invoice_number'/*, 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount'*/, 'txid');

        $totalData     = Booking::where('is_delete', 0)->count();

        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$request->input('order.0.column')];
        //$ordrby = isset( $columns[ 'order'  ] ) ? $columns[ 'columns' ][ $columns[ 'order' ][ 0 ][ 'column' ] ][ 'name' ] : '';
        //$ordrdr = isset( $columns[ 'order'  ] ) ? $columns[ 'order' ][ 0 ][ 'dir' ] : 'asc';
        $dir           = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $bookings = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                ->where('is_delete', 0)
                ->skip($start)
                ->take($limit)
                ->orderBy($order, $dir)
                ->get();
            /*switch( $order ) {
                case 'invoice_number':

                    $bookings = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                        ->where('is_delete', 0)
                        ->skip($start)
                        ->take($limit)
                        ->orderBy( 'invoice_number', $dir )
                        ->get();
                    break;
                case 'txid':
                    $bookings = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                        ->where('is_delete', 0)
                        ->skip($start)
                        ->take($limit)
                        ->orderBy( 'txid', $dir )
                        ->get();
                    break;
                default:
                    $bookings = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                        ->where('is_delete', 0)
                        ->skip($start)
                        ->take($limit)
                        ->get();
            }*/
        }
        else {
            $search   = $request->input('search.value');
            $bookings = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                ->where('is_delete', 0)
                ->where(function($query) use ($search) { /* That's the closure */
                    $query->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('payment_type', 'like', "%{$search}%");
                })
                ->skip($start)
                ->take($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Booking::select('invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'txid')
                ->where('is_delete', 0)
                ->where(function($query) use ($search) { /* That's the closure */
                    $query->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('payment_type', 'like', "%{$search}%");
                })
                ->count();
        }

        $data = array();
        if(!empty($bookings))
        {
            foreach ($bookings as $key=> $booking)
            {
                /* Condition for checking who booked bookings. If a booking collection has temp_user_id then show notification (Booked by cabin owner) otherwise user email. begin*/
                if($booking->temp_user_id != ""){
                    $tempUsers = Tempuser::select('usrFirstname', 'usrLastname', 'usrEmail')
                        ->where('_id', $booking->temp_user_id)
                        ->get();
                    foreach ($tempUsers as $tempUser){
                        $usrEmail = '<span class="label label-info">Booked by cabin owner</span>';
                        $bookings[$key]['usrEmail'] = $usrEmail;
                    }
                }
                else{
                    $users = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail')
                        ->where('_id', $booking->user)
                        ->get();
                    foreach ($users as $user){
                        $usrEmail = $user->usrEmail;
                        $bookings[$key]['usrEmail'] = $usrEmail;
                    }
                }
                /* Condition for checking who booked bookings end*/

                /* Condition for booking status begin */
                if($booking->status == '1') {
                    $bookingStatusLabel = '<span class="label label-primary">New</span>';
                }
                else if ($booking->status == '2') {
                    $bookingStatusLabel = '<span class="label label-warning">Cancelled</span>';
                }
                else if ($booking->status == '3') {
                    $bookingStatusLabel = '<span class="label label-success">Completed</span>';
                }
                else if ($booking->status == '4') {
                    $bookingStatusLabel = '<span class="label label-info">Request</span>';
                }
                else if ($booking->status == '5') {
                    $bookingStatusLabel = '<span class="label label-danger">Failed</span>';
                }
                else {
                    $bookingStatusLabel = '<span class="label label-default">No data</span>';
                }
                /* Condition for payment status end */

                /* Condition for payment status begin */
                if($booking->payment_status == '1') {
                    $paymentStatusLabel = '<span class="label label-success">Done</span>';
                }
                else if ($booking->payment_status == '0') {
                    $paymentStatusLabel = '<span class="label label-danger">Failed</span>';
                }
                else {
                    $paymentStatusLabel = '<span class="label label-default">No data</span>';
                }
                /* Condition for payment status end */

                $nestedData['hash']                    = '<input type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                $nestedData['invoice_number']          = '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'.$booking->_id.'" data-modalID="'.$booking->_id.'">'.$booking->invoice_number.'</a>';
                $nestedData['usrEmail']                = $bookings[$key]['usrEmail'];
                $nestedData['checkin_from']            = ($booking->checkin_from)->format('d.m.y');
                $nestedData['reserve_to']              = ($booking->reserve_to)->format('d.m.y');
                $nestedData['beds']                    = $booking->beds;
                $nestedData['dormitory']               = $booking->dormitory;
                $nestedData['sleeps']                  = $booking->sleeps;
                $nestedData['status']                  = $bookingStatusLabel;
                $nestedData['payment_status']          = $paymentStatusLabel;
                $nestedData['payment_type']            = $booking->payment_type;
                $nestedData['total_prepayment_amount'] = $booking->total_prepayment_amount;
                $nestedData['txid']                    = $booking->txid;
                $data[]                                = $nestedData;
            }
        }

        $json_data = array(
            'draw'            => (int)$request->input('draw'),
            'recordsTotal'    => (int)$totalData,
            'recordsFiltered' => (int)$totalFiltered,
            'data'            => $data
        );

        echo json_encode($json_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $user_id
     * @return \Illuminate\Http\Response
     */
    public function getEmail($user_id)
    {
        /* Write forloop in angular and check bookings contains temp_user_id. If temp_user_id != "" then get details from Tempuser collection otherwise get details from user collection*/
        /* Pass $user_id with identification (temp_user or user) from angular. Then get the identification and write condition (if temp_user get details from Tempuser collection else get details from user collection)*/

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
     * @param  string $id
     * @param  string $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $status, $id)
    {
        $comment                            = $request->input('status_comment');
        if($status == 4){
            $bookingDetails                 = Booking::findOrFail($id);
            $bookingDetails->status_comment = $comment;
            $bookingDetails->status         = 5;
            $bookingDetails->payment_status = 4;
            $bookingDetails->sent_email     = 0;
            $bookingDetails->status_admin   = '581831d0d2ae67c303431d5b'; // Replace this id with AUTH:ID
            $bookingDetails->save();

            /* Functionality to send email about faulty payment begin */
            Mail::send(new FaultyPayment($bookingDetails));
            /* Functionality to send email about faulty payment end */

            $message                        = "Status updated to test";
        }
        else if ($status == 1){
            $bookingDetails                 = Booking::findOrFail($id);
            $bookingDetails->status_comment = $comment;
            $bookingDetails->status         = 1;
            $bookingDetails->payment_status = 1;
            $bookingDetails->sent_email     = 1;
            $bookingDetails->status_admin   = '581831d0d2ae67c303431d5b'; // Replace this id with AUTH:ID
            $bookingDetails->save();

            /* Functionality to send attachment email about payment success begin */
            Mail::send(new SuccessPaymentAttachment($bookingDetails));
            /* Functionality to send attachment email about payment success end */

            $message                        = "Payment done successfully";
        }
        else{
            $bookingDetails                 = Booking::findOrFail($id);
            $bookingDetails->status_comment = $comment;
            $bookingDetails->status         = 5;
            $bookingDetails->payment_status = 0;
            $bookingDetails->status_admin   = '581831d0d2ae67c303431d5b'; // Replace this id with AUTH:ID
            $bookingDetails->save();
            $message                        = "Payment failed";
        }
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
        return response()->json(['message' => 'Invoice send successfully'], 201);
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

        return response()->json(['message' => 'Booking deleted successfully'], 201);
    }
}
