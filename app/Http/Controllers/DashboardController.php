<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabin;
use App\Booking;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;

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
     * Show the form for editing the specified resource.
     *
     * @param  string  $daterange
     * @return \Illuminate\Http\Response
     */
    protected function getDateLabels($daterange){

        $dateFromTo              = explode("-", $daterange);
        if($dateFromTo[0]!='' && $dateFromTo[1]!='')
        {
            $begin = new DateTime( $dateFromTo[0] );
            $end = new DateTime( $dateFromTo[1] );
            $end = $end->modify( '+1 day' );

            $interval = new DateInterval('P1D');
            $period = new DatePeriod($begin, $interval ,$end);
        }

        foreach ($period as $date) {
            $labels[] = $date->format('Ymd');
        }
        return $labels;
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

            $labels                 = $this->getDateLabels($request->daterange);
            /* x- axis labels */
            $xCoord                 = [];
            foreach ($labels as $day){
                $xCoord[] = date('d.m.y', strtotime($day));
            }

            if($cabinName == 'allCabins'){
                $bookings               = Booking::raw(function ($collection) use ($dateBegin, $dateEnd) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'is_delete' => 0,
                                'checkin_from' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                            ],
                        ],
                        [
                            '$group' =>
                                [
                                    '_id' => ['checkin_from' => '$checkin_from'],
                                    'total_prepayment_amount' => ['$sum' => '$total_prepayment_amount'],
                                    'prepayment_amount' => ['$sum' => '$prepayment_amount'],
                                ],
                        ],
                        [
                            '$project' =>
                                [
                                    'checkin_from' => '$_id.checkin_from',
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
            }
            else {
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
            }

            foreach ($bookings as $booking){
                if(!empty($booking->total_prepayment_amount) && !empty($booking->prepayment_amount)) {
                    $checkinFrom                     = $booking->checkin_from->format('Ymd');
                    $totalPrepayAmount[$checkinFrom] = $booking->total_prepayment_amount;
                    $prepayAmount[$checkinFrom]      = $booking->prepayment_amount;
                    $serviceFee[$checkinFrom]        = round($booking->total_prepayment_amount - $booking->prepayment_amount, 2);
                }
            }

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($totalPrepayAmount[$xlabel])){
                    $totalPrepayAmount[$xlabel] = "0";
                }
            }
            ksort($totalPrepayAmount,1);
            $totalPrepay = array_values($totalPrepayAmount);

            foreach ($labels as $xlabel){
                if(!isset($prepayAmount[$xlabel])){
                    $prepayAmount[$xlabel] = "0";
                }
            }
            ksort($prepayAmount,1);
            $prepay     = array_values($prepayAmount);

            foreach ($labels as $xlabel){
                if(!isset($serviceFee[$xlabel])){
                    $serviceFee[$xlabel] = "0";
                }
            }
            ksort($serviceFee,1);
            $service    = array_values($serviceFee);

            $chartData[] =[
                'label'=> __("statisticsAdmin.totalPrepayAmount"),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor'=> 'rgba(255,99,132,1)',
                'borderWidth'=> 1,
                'data' => $totalPrepay,
            ];

            $chartData[] =[
                'label'=> __("statisticsAdmin.prepayAmount"),
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor'=> 'rgba(153, 102, 255, 1)',
                'borderWidth'=> 1,
                'data' => $prepay,
            ];

            $chartData[] =[
                'label'=> __("statisticsAdmin.serviceFee"),
                'backgroundColor' => 'rgba(79, 196, 127, 0.2)',
                'borderColor'=> 'rgba(79, 196, 127, 1)',
                'borderWidth'=> 1,
                'data' => $service,
            ];

            return response()->json(['chartData' => $chartData, 'chartLabel' => $xCoord]);
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
