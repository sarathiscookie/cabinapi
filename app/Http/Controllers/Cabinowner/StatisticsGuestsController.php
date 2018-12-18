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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Creating 30 days default date. When guest statistics page load chart will show with default 30 days count*/
        $monthBegin      = date("d.m.Y");
        $monthEnd        = date("d.m.Y", strtotime('+14 days'));
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
        $test                 = [];
        $xCoord               = [];
        $yCoordSleeps         = [];
        $yCoordMsSleeps       = [];
        $totalHalfBoard       = [];
        $totalSleeps          = [];
        $chartData            = [];
        $requestDateRange     = $request->daterange;
        $explode_dateRange    = explode("-", $requestDateRange);
        $monthBegin           = DateTime::createFromFormat('d.m.Y', $explode_dateRange[0])->format('Y-m-d');
        $monthEnd             = DateTime::createFromFormat('d.m.Y', $explode_dateRange[1])->format('Y-m-d');

        $cabin                = Cabin::select('name')
            ->where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        $generateBookingDates = $this->generateDates($monthBegin, $monthEnd);

        foreach ($generateBookingDates as $generateBookingDate) {

            $generateBookingDat = $generateBookingDate->format('d.m.y');

            $bookings = Booking::select('sleeps', 'halfboard', 'invoice_number')
                ->where('is_delete', 0)
                ->where('cabinname', $cabin->name)
                ->whereIn('status', ['1', '3'])
                ->whereRaw(['checkin_from' => ['$lte' => $this->getDateUtc($generateBookingDat)]])
                ->whereRaw(['reserve_to' => ['$gt' => $this->getDateUtc($generateBookingDat)]])
                ->get();

            $msBookings = MountSchoolBooking::select('sleeps', 'half_board', 'invoice_number')
                ->where('is_delete', 0)
                ->where('cabin_name', $cabin->name)
                ->whereIn('status', ['1', '3'])
                ->whereRaw(['check_in' => ['$lte' => $this->getDateUtc($generateBookingDat)]])
                ->whereRaw(['reserve_to' => ['$gt' => $this->getDateUtc($generateBookingDat)]])
                ->get();

            // Getting count of sleeps, beds and dorms
            if(count($bookings) > 0) {
                $sleeps    = $bookings->sum('sleeps');
                $halfboard = $bookings->sum('halfboard');

                // If normal guest selected halfboard, on graph sum of sleeps (each day) will show.
                if($halfboard > 0) {
                    $halfboardSelectedNormalGuest = $sleeps; //How many normal guest need halfboard facility
                }
                else {
                    $halfboardSelectedNormalGuest = 0;
                }
            }
            else {
                $halfboardSelectedNormalGuest = 0;
                $sleeps       = 0;
            }

            if(count($msBookings) > 0) {
                $msSleeps    = $msBookings->sum('sleeps');
                $msHalfboard = $msBookings->sum('half_board');

                // If ms guest selected halfboard, on graph sum of sleeps (each day) will show.
                if($msHalfboard > 0) {
                    $halfboardSelectedMsGuest = $msSleeps; //How many ms guest need halfboard facility
                }
                else {
                    $halfboardSelectedMsGuest = 0;
                }
            }
            else {
                $msSleeps       = 0;
                $halfboardSelectedMsGuest = 0;
            }

            // Sum of bookings (Mountain School & Normal) on each days
            $totalSleeps[] = (int)$sleeps + $msSleeps;

            // Preparing array
            $prepareArraySleeps      = [$generateBookingDat => $sleeps];
            $prepareArrayMsSleeps    = [$generateBookingDat => $msSleeps];

            // x & y coordinates for marking in graph
            $yCoordSleeps[]          = $sleeps;
            $yCoordMsSleeps[]        = $msSleeps;
            $xCoord[]                = $generateBookingDat;
            $totalHalfBoard[]        = $halfboardSelectedNormalGuest + $halfboardSelectedMsGuest;
        }

        //dd($totalHalfBoard);
        $sleeps_sum = array_sum($totalSleeps);

        // Normal bookings sleeps
        $chartData[] = [
            'label'=> __('statisticsGuests.labelGuest'),
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor'=> 'rgba(54, 162, 235, 1)',
            'borderWidth'=> 1,
            'data' => $yCoordSleeps,
        ];

        // Ms bookings sleeps
        $chartData[] = [
            'label'=> __('statisticsGuests.labelMSchool'),
            'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
            'borderColor'=> 'rgba(255, 206, 86, 1)',
            'borderWidth'=> 1,
            'data' => $yCoordMsSleeps,
        ];

        // Sum of mountain school and normal bookings on each days
        $chartData[] = [
            'label'=> __('statisticsGuests.labelTotalGuestEachDays'),
            'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
            'borderColor'=> 'rgba(153, 102, 255, 1)',
            'borderWidth'=> 1,
            'data' => $totalSleeps,
        ];

        // Total no of halfboard (Mountain school and Normal bookings)
        $chartData[] = [
            'label'=> __('statisticsGuests.labelTotalHalfboard'),
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
            'borderColor'=> 'rgba(255,99,132,1)',
            'borderWidth'=> 1,
            'data' => $totalHalfBoard,
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
