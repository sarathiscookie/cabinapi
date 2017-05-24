<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;
use DateTime;
use Mail;
use App\Mail\BulkInvoiceSend;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($beginDate = null, $endDate = null)
    {
        if($beginDate != '' && $endDate != ''){
            $begin    = new \MongoDB\BSON\UTCDateTime(new DateTime($beginDate)); //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
            $end      = new \MongoDB\BSON\UTCDateTime(new DateTime($endDate)); //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate

            $bookings = Booking::where('is_delete', 0)
                ->where('status', "1")
                ->where('payment_status', "1")
                ->whereBetween('bookingdate', array($begin, $end))
                ->paginate(15);
        }
        else{
            $bookings = Booking::where('is_delete', 0)
                ->where('status', "1")
                ->where('payment_status', "1")
                ->where('bookingdate', '>', new DateTime('-1 month'))
                ->paginate(15);
        }

        return response()->json(['bookingDaterange' => $bookings], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvoice(Request $request)
    {
        // This functionality works when we click SendInvoice button
        $beginDate        = $request->from;
        $endDate          = $request->to;
        $checkbox         = $request->checkbox;   // get checked multiple id from angular and apply foreach

        $begin            = new \MongoDB\BSON\UTCDateTime(new DateTime('2017-03-01')); //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
        $end              = new \MongoDB\BSON\UTCDateTime(new DateTime('2017-03-02')); //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate

        $bookingBulkData  = Booking::where('is_delete', 0)
            ->where('status', "1")
            ->where('payment_status', "1")
            ->whereBetween('bookingdate', array($begin, $end))
            ->limit(2) //instead of limit here where condition will work for check box id
            ->get();

        /* Functionality to send bulk invoice begin */
        foreach($bookingBulkData as $bookingDetails)
        {
            Mail::send(new BulkInvoiceSend($bookingDetails));
        }
        /* Functionality to send bulk invoice end */

        return response()->json(['message' => 'Invoice send successfully'], 201);
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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
