<?php

namespace App\Http\Controllers;

use App\Userlist;
use Illuminate\Http\Request;
use App\Booking;

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
                            '_id' => null,
                            'moneybalance_used' => ['$sum' => '$moneybalance_used'],
                            'count' => ['$sum' => 1]
                        ],
                    ],
                    [
                        '$project' => [
                            'moneybalance_used' => 1,
                            'count' => 1
                        ],
                    ]
                ]);
            });

            $balance_used = [];
            foreach ($balance_used_query as $balance_used_array){
                $balance_used[] = $balance_used_array->moneybalance_used;
                $label[]        = 'How much money balance used: €'.$balance_used_array->moneybalance_used;
            }

            $chartData[] =[
                'label'=> "How much money balance used",
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'data' => $balance_used,
            ];
            /* Functionality for generating chart of money balance used from user credit for another booking end*/

            /* Functionality for generating chart of how much money balance user have in their credit begin*/
            $money_balance_query = Userlist::raw(function ($collection) use ($dateBegin, $dateEnd) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'usrRegistrationDate' => ['$gte' => $dateBegin, '$lte' => $dateEnd]
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'money_balance' => ['$sum' => '$money_balance'],
                            'count' => ['$sum' => 1]
                        ],
                    ],
                    [
                        '$project' => [
                            'money_balance' => 1,
                            'count' => 1
                        ],
                    ]
                ]);
            });

            $money_balance = [];
            foreach ($money_balance_query as $balance_array){
                $money_balance[] = $balance_array->money_balance;
                $label[]         = 'How much money balance user have: €'.$balance_array->money_balance;
            }

            $chartData[] =[
                'label'=> "How much money balance user have",
                'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                'data' => $money_balance,
            ];
            /* Functionality for generating chart of how much money balance user have in their credit */

            return response()->json(['chartData' => $chartData, 'chartLabel' => $label]);
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
