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

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::where('is_delete', 0)
            ->paginate(15);

        foreach ($bookings as $key=> $booking){
            if($booking->temp_user_id != ""){
                $tempUsers = Tempuser::select('usrFirstname', 'usrLastname', 'usrEmail')
                    ->where('_id', $booking->temp_user_id)
                    ->get();
                foreach ($tempUsers as $tempUser){
                    /*$usrEmail = $tempUser->usrEmail;*/
                    $usrEmail = 'cabinowner';
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
        }

        return response()->json(['bookingDetails' => $bookings], 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function sendInvoice(Request $request, $id)
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

        return response()->json(['message' => 'Booking deleted'], 201);
    }
}
