<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabin;
use App\Booking;
use DB;
use Carbon\Carbon;

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
            $bookings               = Booking::raw(function ($collection) use ($cabinName, $dateBegin, $dateEnd) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'cabinname' => $cabinName,
                            'checkin_from' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                        ],
                    ],
                    [
                        '$group' =>
                            [
                                '_id' => ['checkin_from' => '$checkin_from','cabinname' => '$cabinname'],
                                'total_prepayment_amount' => ['$sum' => '$total_prepayment_amount'],
                                'prepayment_amount' => ['$sum' => '$prepayment_amount'],
                            ],
                    ],
                    [
                        '$project' =>
                            [
                                'checkin_from' => '$_id.checkin_from',
                                'cabinname' => '$_id.cabinname',
                                'total_prepayment_amount' => 1,
                                'prepayment_amount' => 1
                            ],
                    ],
                    [
                        '$sort' =>
                            [
                                'checkin_from' => 1
                            ],
                    ],
                ]);
            });

            $totalPrepayAmount       = [];
            $prepayAmount            = [];
            $checkinFrom             = [];
            $serviceFee              = [];
            foreach ($bookings as $booking){
                $checkinFrom[]       = $booking->checkin_from->format('d.m.y');
                $totalPrepayAmount[] = $booking->total_prepayment_amount;
                $prepayAmount[]      = $booking->prepayment_amount;
                $serviceFee[]        = round($booking->total_prepayment_amount - $booking->prepayment_amount, 2);
            }

            $chartData[] =[
                'label'=> __("statisticsAdmin.totalPrepayAmount"),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor'=> 'rgba(255,99,132,1)',
                'borderWidth'=> 1,
                'data' => $totalPrepayAmount,
            ];

            $chartData[] =[
                'label'=> __("statisticsAdmin.prepayAmount"),
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor'=> 'rgba(153, 102, 255, 1)',
                'borderWidth'=> 1,
                'data' => $prepayAmount,
            ];

            $chartData[] =[
                'label'=> __("statisticsAdmin.serviceFee"),
                'backgroundColor' => 'rgba(79, 196, 127, 0.2)',
                'borderColor'=> 'rgba(79, 196, 127, 1)',
                'borderWidth'=> 1,
                'data' => $serviceFee,
            ];
            
            return response()->json(['chartData' => unserialize(str_replace(array('NAN;','INF;'),'0;',serialize($chartData))), 'chartLabel' => $checkinFrom]);
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
