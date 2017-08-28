<?php

namespace App\Http\Controllers;

use App\Usercreditchart;
use Illuminate\Http\Request;
use App\Booking;
use DateTime;
use DateInterval;
use DatePeriod;

class UserCreditStatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.userCreditStatistics');
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
        if(!empty($request->daterange))
        {
            $daterange              = explode("-", $request->daterange);
            $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($daterange[0])*1000);
            $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($daterange[1])*1000);

            $labels                 = $this->getDateLabels($request->daterange);
            /* x- axis labels */
            $xCoord                 = [];
            foreach ($labels as $day){
                $xCoord[] = date('d.m.y', strtotime($day));
            }

            /* Functionality for generating money balance used from user credit for another booking begin*/
            $balance_used_query = Booking::raw(function ($collection) use ($dateBegin, $dateEnd) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'checkin_from' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => ['checkin_from' => '$checkin_from'],
                            'moneybalance_used' => ['$sum' => '$moneybalance_used'],
                            'count' => ['$sum' => 1]
                        ],
                    ],
                    [
                        '$project' => [
                            'checkin_from' => '$_id.checkin_from',
                            'moneybalance_used' => 1,
                            'count' => 1
                        ],
                    ],
                    [
                        '$sort' =>
                            [
                                'checkin_from' => 1
                            ],
                    ]
                ]);
            });

            $total_balance_used = [];
            foreach ($balance_used_query as $balance_used_array){
                $checkinFrom                = $balance_used_array->checkin_from->format('Ymd');
                $balance_used[$checkinFrom] = $balance_used_array->moneybalance_used;
                $total_balance_used[]       = $balance_used_array->moneybalance_used;
            }
            $total_balance_used_array_sum   = array_sum($total_balance_used);

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($balance_used[$xlabel])){
                    $balance_used[$xlabel] = "0";
                }
            }
            ksort($balance_used,1);
            $totalBalanceUsed = array_values($balance_used);

            $chartData[] =[
                'label'=> __('admin.labelOne'),
                'backgroundColor' => 'rgba(79, 196, 127, 0.2)',
                'borderColor'=> 'rgba(79, 196, 127, 1)',
                'borderWidth'=> 1,
                'data' => $totalBalanceUsed,
            ];
            /* Functionality for generating chart of money balance used from user credit for another booking end*/

            /* Functionality for generating chart of how much money balance user have in their credit begin*/

            $money_balance_query = Usercreditchart::raw(function ($collection) use ($dateBegin, $dateEnd) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'usrRegistrationDate' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => ['year' => ['$year' => '$usrRegistrationDate'], 'month' => ['$month' => '$usrRegistrationDate'], 'day' => ['$dayOfMonth' => '$usrRegistrationDate']],
                            'money_balance' => ['$sum' => '$money_balance'],
                            'count' => ['$sum' => 1]
                        ],
                    ],
                    [
                        '$project' => [
                            'year' => '$_id.year',
                            'month' => '$_id.month',
                            'day' => '$_id.day',
                            'money_balance' => 1,
                            'count' => 1
                        ],
                    ],
                    [
                        '$sort' =>
                            [
                                'usrRegistrationDate' => 1
                            ],
                    ]
                ]);
            });

            $total_money_balance = [];
            foreach ($money_balance_query as $balance_array){
                $yearMonthDate                       = $balance_array->year.$balance_array->month.$balance_array->day;
                $usrRegistrationDate                 = $yearMonthDate;
                $money_balance[$usrRegistrationDate] = $balance_array->money_balance;
                $total_money_balance[]               = $balance_array->money_balance;
            }
            $total_money_balance_array_sum           = array_sum($total_money_balance);

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($money_balance[$xlabel])){
                    $money_balance[$xlabel] = "0";
                }
            }
            ksort($money_balance,1);
            $totalMoneyBalance = array_values($money_balance);

            $chartData[] =[
                'label'=> __('admin.labelTwo'),
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor'=> 'rgba(153, 102, 255, 1)',
                'borderWidth'=> 1,
                'data' => $totalMoneyBalance,
            ];
            /* Functionality for generating chart of how much money balance user have in their credit */


            /* Functionality for generating chart of how much money deleted from user credit begin*/
            $money_deleted_query = Usercreditchart::raw(function ($collection) use ($dateBegin, $dateEnd) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'usrRegistrationDate' => ['$gte' => $dateBegin, '$lte' => $dateEnd],
                            'money_balance' => 0
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => ['year' => ['$year' => '$usrRegistrationDate'], 'month' => ['$month' => '$usrRegistrationDate'], 'day' => ['$dayOfMonth' => '$usrRegistrationDate']],
                            'count' => ['$sum' => 1]
                        ],
                    ],
                    [
                        '$project' => [
                            'year' => '$_id.year',
                            'month' => '$_id.month',
                            'day' => '$_id.day',
                            'count' => 1
                        ],
                    ],
                    [
                        '$sort' =>
                            [
                                'usrRegistrationDate' => 1
                            ],
                    ]
                ]);
            });

            $total_money_deleted = [];
            foreach ($money_deleted_query as $deleted_array){
                $yearMonthDate                       = $deleted_array->year.$deleted_array->month.$deleted_array->day;
                $usrRegistrationDate                 = $yearMonthDate;
                $money_deleted[$usrRegistrationDate] = $deleted_array->count;
                $total_money_deleted[]               = $deleted_array->count;
            }
            $total_money_deleted_array_sum           = array_sum($total_money_deleted);

            /* y- axis graph data */
            foreach ($labels as $xlabel){
                if(!isset($money_deleted[$xlabel])){
                    $money_deleted[$xlabel] = "0";
                }
            }
            ksort($money_deleted,1);
            $totalMoneyDeleted = array_values($money_deleted);

            $chartData[] =[
                'label'=> __('admin.labelThree'),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor'=> 'rgba(255,99,132,1)',
                'borderWidth'=> 1,
                'data' => $totalMoneyDeleted,
            ];
            /* Functionality for generating chart of how much money deleted from user credit end */

            return response()->json(['chartData' => $chartData, 'chartLabel' => $xCoord, 'total_balance_used_array_sum' => $total_balance_used_array_sum, 'total_money_balance_array_sum' => $total_money_balance_array_sum, 'total_money_deleted_array_sum' => $total_money_deleted_array_sum]);
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
