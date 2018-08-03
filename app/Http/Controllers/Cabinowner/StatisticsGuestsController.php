<?php

namespace App\Http\Controllers\Cabinowner;

use App\MountSchoolBooking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Booking;
use App\Cabin;
use Auth;

class StatisticsGuestsController extends Controller
{
    /**
     * To generate date between two dates.
     *
     * @param  string  $now
     * @param  string  $end
     * @return \Illuminate\Http\Response
     */
    protected function generateDates($now, $end){
        $period = new DatePeriod(
            new DateTime($now),
            new DateInterval('P1D'),
            new DateTime($end)
        );

        return $period;
    }

    /**
     * To generate date format as mongo.
     *
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    protected function getDateUtc($date)
    {
        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime         = new DateTime($dateFormatChange);
        $timeStamp        = $dateTime->getTimestamp();
        $utcDateTime      = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);
        return $utcDateTime;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $daterange
     * @return array
     */
    protected function getDateLabels($daterange = null, $monthBegin = null, $monthEnd = null)
    {
        $labels       = [];
        if($daterange === null) {
            $begin    = new DateTime( $monthBegin );
            $end      = new DateTime( $monthEnd );
            $end      = $end->modify( '+1 day' );

            $interval = new DateInterval('P1D');
            $period   = new DatePeriod($begin, $interval ,$end);

            foreach ($period as $date) {
                $labels[] = $date->format('Ymd');
            }
        }
        else {
            $dateFromTo   = explode("-", $daterange);

            if($dateFromTo[0] != '' && $dateFromTo[1] != '')
            {
                $begin    = new DateTime( $dateFromTo[0] );
                $end      = new DateTime( $dateFromTo[1] );
                $end      = $end->modify( '+1 day' );

                $interval = new DateInterval('P1D');
                $period   = new DatePeriod($begin, $interval ,$end);

                foreach ($period as $date) {
                    $labels[] = $date->format('Ymd');
                }
            }
        }

        return $labels;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Creating 30 days default date. When guest statistics page load chart will show with default 30 days count*/
        $monthBegin      = date("d.m.Y");
        $monthEnd        = date("d.m.Y", strtotime('+30 days'));
        $defaultDate     = $monthBegin.'-'.$monthEnd;

        return view('cabinowner.statisticsGuests', ['defaultDate' => $defaultDate]);
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
        $array_sleeps_normal_booking = [];
        $array_sleeps_ms_booking     = [];
        $xCoord                      = [];
        $sleeps_sum                  = 0;
        $explode_dateRange           = explode("-", $request->daterange);
        $monthBegin                  = DateTime::createFromFormat("d.m.Y", $explode_dateRange[0])->format('Y-m-d');
        $monthEnd                    = DateTime::createFromFormat("d.m.Y", $explode_dateRange[1])->format('Y-m-d');
        $generateBookingDates        = $this->generateDates($monthBegin, $monthEnd);
        $labels                      = $this->getDateLabels($daterange = null, $monthBegin, $monthEnd);
        $cabin                       = Cabin::select('name')
            ->where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        /* x- axis labels */
        foreach ($labels as $day){
            $xCoord[] = date('d.m.y', strtotime($day));
        }

        foreach ($generateBookingDates as $generateBookingDate) {
            /* Normal booking chart */
            $normalBookings        = Booking::raw(function($collection) use ($generateBookingDate, $cabin){
                return $collection->aggregate([
                   [
                       '$match' => [
                           'is_delete' => 0,
                           'cabinname' => $cabin->name,
                           'status' => '1',
                           'checkin_from' => ['$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y'))],
                           'reserve_to' => ['$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y'))]
                       ]
                   ],
                   [
                       '$group' => [
                           '_id' => ['checkin_from' => '$checkin_from'],
                           'sleeps' => ['$sum' => '$sleeps'],
                           'count' => ['$sum' => 1]
                       ]
                   ],
                   [
                       '$project' => [
                           'checkin_from' => '$_id.checkin_from',
                           'sleeps' => 1,
                           'count' => 1
                       ]
                   ],
                   [
                       '$sort' => [
                               'checkin_from' => 1
                       ]
                   ]
                ]);
            });

            foreach ($normalBookings as $normalBooking){
                $checkinFrom                   = $normalBooking->checkin_from->format('Ymd');
                $sleeps[$checkinFrom]          = $normalBooking->sleeps;
                $array_sleeps_normal_booking[] = $normalBooking->sleeps;
            }

            /* Mountain school booking chart */
            $msBookings        = MountSchoolBooking::raw(function($collection) use ($generateBookingDate, $cabin){
                return $collection->aggregate([
                    [
                        '$match' => [
                            'is_delete' => 0,
                            'cabin_name' => $cabin->name,
                            'status' => '1',
                            'check_in' => ['$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y'))],
                            'reserve_to' => ['$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y'))]
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => ['check_in' => '$check_in'],
                            'sleeps' => ['$sum' => '$sleeps'],
                            'count' => ['$sum' => 1]
                        ]
                    ],
                    [
                        '$project' => [
                            'check_in' => '$_id.check_in',
                            'sleeps' => 1,
                            'count' => 1
                        ]
                    ],
                    [
                        '$sort' => [
                            'check_in' => 1
                        ]
                    ]
                ]);
            });

            foreach ($msBookings as $msBooking){
                $check_in                  = $msBooking->check_in->format('Ymd');
                $msSleeps[$check_in]       = $msBooking->sleeps;
                $array_sleeps_ms_booking[] = $msBooking->sleeps;
            }

            $sleeps_sum   = round(array_sum($array_sleeps_ms_booking) + array_sum($array_sleeps_normal_booking), 2);
        }

        /* y- axis graph data */
        foreach ($labels as $xlabel){
            if(!isset($sleeps[$xlabel])){
                $sleeps[$xlabel] = "0";
            }
        }

        /* Normal booking chart */
        ksort($sleeps,1);
        $totalSleepsNormalBooking = array_values($sleeps);

        $chartData[] =[
            'label'=> __('statisticsGuests.labelGuest'),
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor'=> 'rgba(54, 162, 235, 1)',
            'borderWidth'=> 1,
            'data' => $totalSleepsNormalBooking,
        ];

        /* Ms booking chart */
        /* y- axis graph data */
        foreach ($labels as $xlabel){
            if(!isset($msSleeps[$xlabel])){
                $msSleeps[$xlabel] = "0";
            }
        }

        ksort($msSleeps,1);
        $totalSleepsMsBooking = array_values($msSleeps);

        $chartData[] =[
            'label'=> __('statisticsGuests.labelMSchool'),
            'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
            'borderColor'=> 'rgba(153, 102, 255, 1)',
            'borderWidth'=> 1,
            'data' => $totalSleepsMsBooking,
        ];

        return response()->json(['chartData' => $chartData, 'chartLabel' => $xCoord, 'sleeps_sum' => $sleeps_sum]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
