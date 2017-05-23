<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @uses $gte::it selects the documents where the value of the field is greater than or equal to (i.e. >=)
     * @uses $lte::it selects the documents where the value of the field is less than or equal to (i.e. <=)
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Fetching results of 30 days */
        $currentDate    = Carbon::now(); // get the current time
        $startDate      = $currentDate->subDays(30); // add 30 days to the current time
        $endDate        = Carbon::now(); // get the current time

        $start = new DateTime("2017-01-01 00:00:00");
        $stop = new DateTime("2017-01-30 00:00:00");

        //dd($startDate.' - '.$endDate); //2017-04-23 - 2017-05-23
        $invoice = Invoice::where('is_delete', 0)
            ->where('status', "1")
            ->where('payment_status', "1")
           /* ->where('bookingdate', '>', new DateTime('-2 months'))*/
            ->whereBetween('bookingdate', array($start, $stop))
            /*->whereRaw(['bookingdate' => array('$gte' => $startDate, '$lte' => $endDate)])*/
            ->get();

        dd($invoice);
        return response()->json(['invoice' => $invoice], 200);
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
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
