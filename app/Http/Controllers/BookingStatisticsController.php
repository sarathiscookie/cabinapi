<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;

class BookingStatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.bookingsStatistics');
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
            $sum_of_fix             = [];
            $sum_of_cancelled       = [];
            $sum_of_waiting         = [];
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

            /* Booking status statistics begin */
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
                                    '_id' => ['checkin_from' => '$checkin_from','status' => '$status'],
                                    'prepayment_amount' => ['$sum' => '$prepayment_amount'],
                                    'count' => ['$sum' => 1]
                                ],
                        ],
                        [
                            '$project' =>
                                [
                                    'checkin_from' => '$_id.checkin_from',
                                    'status' => '$_id.status',
                                    'prepayment_amount' => 1,
                                    'count' => 1,
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
                                    '_id' => ['checkin_from' => '$checkin_from','cabinname' => '$cabinname','status' => '$status'],
                                    'prepayment_amount' => ['$sum' => '$prepayment_amount'],
                                    'count' => ['$sum' => 1]
                                ],
                        ],
                        [
                            '$project' =>
                                [
                                    'checkin_from' => '$_id.checkin_from',
                                    'cabinname' => '$_id.cabinname',
                                    'status' => '$_id.status',
                                    'prepayment_amount' => 1,
                                    'count' => 1,
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



            foreach ($bookings as $row){
                $checkinFrom          = $row->checkin_from->format('Ymd');
                if($row->status == "1")
                {
                    $count[$checkinFrom]      = $row->count;
                    $sum_of_fix[$checkinFrom] = $row->prepayment_amount;
                }
                if($row->status == "2")
                {
                    $cancelled[$checkinFrom]        = $row->count;
                    $sum_of_cancelled[$checkinFrom] = $row->prepayment_amount;
                }
                if($row->status == "5")
                {
                    $waiting[$checkinFrom]        = $row->count;
                    $sum_of_waiting[$checkinFrom] = $row->prepayment_amount;
                }
                /*if($row->status == "3")
                {
                    $completed[$checkinFrom] = $row->count;
                }*/
            }

            $total_fix       = array_sum($sum_of_fix);
            $total_cancelled = array_sum($sum_of_cancelled);
            $total_waiting   = array_sum($sum_of_waiting);

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($count[$xlabel])){
                    $count[$xlabel] = "0";
                }
            }
            ksort($count,1);
            $fix    = array_values($count);

            foreach ($labels as $xlabel){
                if(!isset($cancelled[$xlabel])){
                    $cancelled[$xlabel] = "0";
                }
            }
            ksort($cancelled,1);
            $canc   = array_values($cancelled);

            foreach ($labels as $xlabel){
                if(!isset($waiting[$xlabel])){
                    $waiting[$xlabel] = "0";
                }
            }
            ksort($waiting,1);
            $wait  = array_values($waiting);

            /*foreach ($labels as $xlabel){
                if(!isset($completed[$xlabel])){
                    $completed[$xlabel] = "0";
                }
            }

            ksort($completed,1);
            $compl  = array_values($completed);*/

            $chartData[] =[
                'label'=>__('bookingStatistics.graphFixLabel'),
                'backgroundColor' => 'rgba(79, 196, 127, 0.2)',
                'borderColor'=> 'rgba(79, 196, 127, 1)',
                'borderWidth'=> 1,
                'data' => $fix,
            ];

            $chartData[] =[
                'label'=> __('bookingStatistics.graphCancelLabel'),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor'=> 'rgba(255, 99, 132, 1)',
                'borderWidth'=> 1,
                'data' => $canc,
            ];

            $chartData[] =[
                'label'=> __('bookingStatistics.graphWaitingLabel'),
                'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                'borderColor'=> 'rgba(255, 159, 64, 1)',
                'borderWidth'=> 1,
                'data' => $wait,
            ];

            /*$chartData[] =[
                'label'=> 'Abgeschlossen',
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor'=> 'rgba(153, 102, 255, 1)',
                'borderWidth'=> 1,
                'data' => $compl,
            ];*/
            /* Booking status statistics end */

            /* Cancelled positive and negative begin */
            if($cabinName == 'allCabins'){
                $bookings_negative_positive  = Booking::raw(function ($collection) use ($dateBegin, $dateEnd) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'is_delete' => 0,
                                'status' => '2',
                                'checkin_from' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                            ],
                        ],
                        [
                            '$group' =>
                                [
                                    '_id' => ['checkin_from' => '$checkin_from','cancel_status' => '$cancel_status'],
                                    'count' => ['$sum' => 1]
                                ],
                        ],
                        [
                            '$project' =>
                                [
                                    'checkin_from' => '$_id.checkin_from',
                                    'status' => '$_id.status',
                                    'cancel_status' => '$_id.cancel_status',
                                    'count' => 1,
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
                $bookings_negative_positive  = Booking::raw(function ($collection) use ($cabinName, $dateBegin, $dateEnd) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'is_delete' => 0,
                                'cabinname' => $cabinName,
                                'status' => '2',
                                'checkin_from' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                            ],
                        ],
                        [
                            '$group' =>
                                [
                                    '_id' => ['checkin_from' => '$checkin_from','cabinname' => '$cabinname','cancel_status' => '$cancel_status'],
                                    'count' => ['$sum' => 1]
                                ],
                        ],
                        [
                            '$project' =>
                                [
                                    'checkin_from' => '$_id.checkin_from',
                                    'cabinname' => '$_id.cabinname',
                                    'status' => '$_id.status',
                                    'cancel_status' => '$_id.cancel_status',
                                    'count' => 1,
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


            foreach ($bookings_negative_positive as $row_negative_positive){
                $checkin_cancel_status = $row_negative_positive->checkin_from->format('Ymd');
                if($row_negative_positive->cancel_status == 1)
                {
                    $gotMoney[$checkin_cancel_status]    = $row_negative_positive->count;
                }
                if($row_negative_positive->cancel_status == 0)
                {
                    $notGetMoney[$checkin_cancel_status] = $row_negative_positive->count;
                }
            }

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($gotMoney[$xlabel])){
                    $gotMoney[$xlabel] = "0";
                }
            }
            ksort($gotMoney,1);
            $get    = array_values($gotMoney);

            foreach ($labels as $xlabel){
                if(!isset($notGetMoney[$xlabel])){
                    $notGetMoney[$xlabel] = "0";
                }
            }
            ksort($notGetMoney,1);
            $notGet = array_values($notGetMoney);

            $chartData[] =[
                'label'=>  __('bookingStatistics.graphGotMoneyLabel'),
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor'=> 'rgba(54, 162, 235, 1)',
                'borderWidth'=> 1,
                'data' => $get,
            ];

            $chartData[] =[
                'label'=>  __('bookingStatistics.graphNotGetMoneyLabel'),
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor'=> 'rgba(153, 102, 255, 1)',
                'borderWidth'=> 1,
                'data' => $notGet,
            ];
            /* Cancelled positive and negative end */

            return response()->json(['chartData' => $chartData, 'chartLabel' => $xCoord, 'total_fix' => $total_fix, 'total_cancelled' => $total_cancelled, 'total_waiting' => $total_waiting]);
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
}
