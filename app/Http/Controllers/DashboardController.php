<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabin;
use App\Booking;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.dashboard');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(!empty($request->cabin) && !empty($request->daterange))
        {
            $cabinName              = $request->cabin;
            $daterange              = explode("-", $request->daterange);
            $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($daterange[0])*1000);
            $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($daterange[1])*1000);
            $bookings               = Booking::where('is_delete', 0)
                ->where('cabinname', $cabinName)
                ->whereBetween('bookingdate', [$dateBegin, $dateEnd])
                ->groupBy('checkin_from')
                ->count();
            return response()->json(['incomingCount' => $bookings]);
        }


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

    /**
     * Collecting cabins.
     *
     * @return \Illuminate\Http\Response
     */
    public function cabins()
    {
        $cabins = Cabin::select('name')
            ->where('is_delete', 0)
            ->where('other_cabin', '0')
            ->get();

        return $cabins;
    }
}
