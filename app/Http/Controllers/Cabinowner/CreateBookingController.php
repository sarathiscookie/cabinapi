<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Booking;
use App\MountSchoolBooking;
use App\Season;
use App\Country;
use App\Userlist;
use App\Cabin;
use DateTime;
use DatePeriod;
use DateInterval;
use Auth;

class CreateBookingController extends Controller
{
    /**
     * No of beds, dorms and sleeps.
     *
     */
    public function noBedsDormsSleeps()
    {
        $numbers = array(
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '10' => 10,
            '11' => 11,
            '12' => 12,
            '13' => 13,
            '14' => 14,
            '15' => 15,
            '16' => 16,
            '17' => 17,
            '18' => 18,
            '19' => 19,
            '20' => 20,
            '21' => 21,
            '22' => 22,
            '23' => 23,
            '24' => 24,
            '25' => 25,
            '26' => 26,
            '27' => 27,
            '28' => 28,
            '29' => 29,
            '30' => 30,
        );

        return $numbers;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noBedsDormsSleeps = $this->noBedsDormsSleeps();

        $country           = Country::select('name')
            ->get();

        $monthBegin        = date("Y-m-d");
        $monthEnd          = date("Y-m-t 23:59:59");

        $holiday_prepare   = [];
        $disableDates      = [];

        $seasons           = Season::where('cabin_owner', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->where('cabin_id', new \MongoDB\BSON\ObjectID(session('cabin_id')))
            ->get();

        if($seasons) {

            $generateDates = $this->generateDates($monthBegin, $monthEnd);

            foreach ($generateDates as $generateDate) {

                $dates = $generateDate->format('Y-m-d');
                $day = $generateDate->format('D');

                foreach ($seasons as $season) {

                    if (($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($dates >= ($season->earliest_summer_open)->format('Y-m-d')) && ($dates < ($season->latest_summer_close)->format('Y-m-d'))) {
                        $holiday_prepare[] = ($season->summer_mon === 1) ? 'Mon' : 0;
                        $holiday_prepare[] = ($season->summer_tue === 1) ? 'Tue' : 0;
                        $holiday_prepare[] = ($season->summer_wed === 1) ? 'Wed' : 0;
                        $holiday_prepare[] = ($season->summer_thu === 1) ? 'Thu' : 0;
                        $holiday_prepare[] = ($season->summer_fri === 1) ? 'Fri' : 0;
                        $holiday_prepare[] = ($season->summer_sat === 1) ? 'Sat' : 0;
                        $holiday_prepare[] = ($season->summer_sun === 1) ? 'Sun' : 0;
                    } elseif (($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($dates >= ($season->earliest_winter_open)->format('Y-m-d')) && ($dates < ($season->latest_winter_close)->format('Y-m-d'))) {
                        $holiday_prepare[] = ($season->winter_mon === 1) ? 'Mon' : 0;
                        $holiday_prepare[] = ($season->winter_tue === 1) ? 'Tue' : 0;
                        $holiday_prepare[] = ($season->winter_wed === 1) ? 'Wed' : 0;
                        $holiday_prepare[] = ($season->winter_thu === 1) ? 'Thu' : 0;
                        $holiday_prepare[] = ($season->winter_fri === 1) ? 'Fri' : 0;
                        $holiday_prepare[] = ($season->winter_sat === 1) ? 'Sat' : 0;
                        $holiday_prepare[] = ($season->winter_sun === 1) ? 'Sun' : 0;
                    }

                }

                $prepareArray    = [$dates => $day];
                $array_unique    = array_unique($holiday_prepare);
                $array_intersect = array_intersect($prepareArray, $array_unique);

                foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                    $disableDates[] = $array_intersect_key;
                }

            }
        }

        return view('cabinowner.createBooking', ['noBedsDormsSleeps' => $noBedsDormsSleeps, 'country' => $country, 'disableDates' => $disableDates]);
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
     * @param  \App\Http\Requests\CreateBookingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookingRequest $request)
    {
        if(isset($request->createBooking) && $request->createBooking === 'createBooking' && session()->has('availableSuccess') && session('availableSuccess') === 'success') {
            $requestBeds      = 0;
            $requestDorms     = 0;
            $requestSleeps    = 0;
            $invoiceNumber    = '';

            $availableSuccess = session('availableSuccess');

            if(session()->has('requestBeds') && session('requestBeds') != '') {
                $requestBeds = (int)session('requestBeds');
            }

            if(session()->has('requestDorms') && session('requestDorms') != '') {
                $requestDorms = (int)session('requestDorms');
            }

            if(session()->has('requestSleeps') && session('requestSleeps') != '') {
                $requestSleeps = (int)session('requestSleeps');
            }

            /* Storing user details begin */
            $user                      = new Userlist;
            $user->usrFirstname        = $request->firstname;
            $user->usrLastname         = $request->lastname;
            $user->usrAddress          = $request->street;
            $user->usrCity             = $request->city;
            $user->usrCountry          = $request->country;
            $user->usrZip              = $request->zip;
            $user->usrEmail            = $request->email;
            $user->usrTelephone        = $request->phone;
            $user->usrMobile           = $request->mobile;
            $user->usrActive           = '0';
            $user->usrlId              = 3;
            $user->is_delete           = 0;
            $user->save();
            /* Storing user details end */

            //dd(' requestSleeps '.$requestSleeps.' requestBeds: '.$requestBeds. ' requestDorms: '. $requestDorms .' availableSuccess: '.$availableSuccess);

            /* Storing booking details begin */

            /* Create invoice number begin */
            if(session()->has('invoice_autonum') && session('invoice_autonum') != '') {
                $autoNumber = (int)session('invoice_autonum') + 1;
            }
            else {
                $autoNumber = 100000;
            }

            if(session()->has('invoice_code') && session('invoice_code') != '') {
                $invoiceCode   = session('invoice_code');
                $invoiceNumber = $invoiceCode . "-" . date("y") . "-" . $autoNumber;
            }
            /* Create invoice number end */

            $booking                   = new Booking;
            $booking->cabinname        = session('cabin_name');
            $booking->checkin_from     = $this->getDateUtc(session('dateFrom'));
            $booking->reserve_to       = $this->getDateUtc(session('dateTo'));
            $booking->user             = new \MongoDB\BSON\ObjectID($user->_id);
            $booking->invoice_number   = $invoiceNumber;
            $booking->beds             = $requestBeds;
            $booking->dormitory        = $requestDorms;
            $booking->sleeps           = (session('sleeping_place') == 1) ? $requestSleeps : $requestBeds + $requestDorms;
            $booking->guests           = (session('sleeping_place') == 1) ? $requestSleeps : $requestBeds + $requestDorms;
            $booking->bookingdate      = date('Y-m-d H:i:s');
            $booking->status           = "1";
            $booking->payment_status   = "2";
            $booking->halfboard        = $request->halfboard;
            $booking->comments         = $request->comments;
            $booking->is_delete        = 0;
            $booking->save();

            /* Update cabin invoice_autonum begin */
            Cabin::where('is_delete', 0)
                ->where('name', session('cabin_name'))
                ->where('cabin_owner', Auth::user()->_id)
                ->update(['invoice_autonum' => $autoNumber]);
            /* Update cabin invoice_autonum end */

            /* Storing booking details end */

            $request->session()->flash('successBooking', 'Well Done! Booking done successfully.');
            return redirect(url('cabinowner/bookings'));
        }
        else {
            abort(404);
        }
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

    /**
     * Check available data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $available = 'failure';

        if($request->search == 'searchAvailability') {
            if(session('sleeping_place') != 1)
            {
                $this->validate($request, [
                    'dateFrom' => 'required',
                    'dateTo' => 'required',
                    'beds' => 'required_without:dorms',
                    'dorms' => 'required_without:beds',
                ]);
            }
            else {
                $this->validate($request, [
                    'dateFrom' => 'required',
                    'dateTo' => 'required',
                    'sleeps' => 'required|not_in:0',
                ]);
            }

            if($request->dateFrom != null && $request->dateTo != null) {

                $holiday_prepare        = [];
                $regular_dates_array    = [];
                $not_regular_dates      = [];

                $dorms                  = 0;
                $beds                   = 0;
                $sleeps                 = 0;

                $msSleeps               = 0;
                $msBeds                 = 0;
                $msDorms                = 0;

                $availableStatus        = [];

                $dateBegin              = DateTime::createFromFormat('d.m.y', $request->dateFrom)->format('Y-m-d');
                $dateEnd                = DateTime::createFromFormat('d.m.y', $request->dateTo)->format('Y-m-d');
                $dateDifference         = date_diff(date_create($dateBegin), date_create($dateEnd));

                $generateBookingDates   = $this->generateDates($dateBegin, $dateEnd);

                $seasons                = Season::where('cabin_owner', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
                    ->where('cabin_id', new \MongoDB\BSON\ObjectID(session('cabin_id')))
                    ->get();

                foreach ($generateBookingDates as $key => $generateBookingDate) {

                    if($dateDifference->format("%a") <= 60) {

                        $generateBookingDat   = $generateBookingDate->format('Y-m-d'); //2017-09-02,2017-09-03,2017-09-04,2017-09-05,2017-09-06,2017-09-07,2017-09-08,2017-09-09,2017-09-10,2017-09-11
                        $generateBookingDay   = $generateBookingDate->format('D'); //Sat,Sun,Mon,Tue,Wed,Thu,Fri,Sat,Sun,Mon
                        $bookingDateSeasonType = null;

                        /* Checking season begin */
                        if($seasons) {
                            foreach($seasons as $season) {
                                if( ($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($generateBookingDat >= ($season->earliest_summer_open)->format('Y-m-d')) && ($generateBookingDat < ($season->latest_summer_close)->format('Y-m-d')) ) {
                                    //print_r($generateBookingDat. ' booked on summer season ');
                                    $holiday_prepare[] = ($season->summer_mon === 1) ? 'Mon' : 0;
                                    $holiday_prepare[] = ($season->summer_tue === 1) ? 'Tue' : 0;
                                    $holiday_prepare[] = ($season->summer_wed === 1) ? 'Wed' : 0;
                                    $holiday_prepare[] = ($season->summer_thu === 1) ? 'Thu' : 0;
                                    $holiday_prepare[] = ($season->summer_fri === 1) ? 'Fri' : 0;
                                    $holiday_prepare[] = ($season->summer_sat === 1) ? 'Sat' : 0;
                                    $holiday_prepare[] = ($season->summer_sun === 1) ? 'Sun' : 0;
                                    /* 1   0000 1   0 1   00000 1   1   00000 1 */
                                    /* Mon 0000 Sat 0 Mon 00000 Sun Mon 00000 Sun */
                                    $bookingDateSeasonType = 'summer';
                                    break;
                                }
                                elseif( ($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($generateBookingDat >= ($season->earliest_winter_open)->format('Y-m-d')) && ($generateBookingDat < ($season->latest_winter_close)->format('Y-m-d')) ) {
                                    //print_r($generateBookingDat. ' booked on winter season ');
                                    $holiday_prepare[] = ($season->winter_mon === 1) ? 'Mon' : 0;
                                    $holiday_prepare[] = ($season->winter_tue === 1) ? 'Tue' : 0;
                                    $holiday_prepare[] = ($season->winter_wed === 1) ? 'Wed' : 0;
                                    $holiday_prepare[] = ($season->winter_thu === 1) ? 'Thu' : 0;
                                    $holiday_prepare[] = ($season->winter_fri === 1) ? 'Fri' : 0;
                                    $holiday_prepare[] = ($season->winter_sat === 1) ? 'Sat' : 0;
                                    $holiday_prepare[] = ($season->winter_sun === 1) ? 'Sun' : 0;
                                    /* 000000 1   0 1   00000 1   000000 */
                                    /* 000000 Sun 0 Tue 00000 Mon 000000 */
                                    $bookingDateSeasonType = 'winter';
                                    break;
                                }
                            }

                            if (!$bookingDateSeasonType)
                            {
                                //print_r($generateBookingDat . ' Sorry not a season time ');
                                return response()->json(['error' => 'Sorry selected dates are not in a season time.'], 422);
                            }
                            /*else
                            {
                                print_r($generateBookingDat . ' booked on ' . $bookingDateSeasonType . ' season ');
                            }*/

                            $prepareArray           = [$generateBookingDat => $generateBookingDay];
                            $array_unique           = array_unique($holiday_prepare);
                            $array_intersect        = array_intersect($prepareArray,$array_unique);

                            foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {

                                if($dateBegin === $array_intersect_key) {
                                    return response()->json(['error' => $array_intersect_values.' is a holiday.'], 422);
                                }

                                if($array_intersect_key > $dateBegin && $array_intersect_key < $dateEnd) {
                                    return response()->json(['error' => 'Booking not possible because holidays included.'], 422);
                                }

                            }
                        }
                        /* Checking season end */

                        /* Checking bookings available begin */
                        $session_mon_day      = (session('mon_day') === 1) ? 'Mon' : 0;
                        $session_tue_day      = (session('tue_day') === 1) ? 'Tue' : 0;
                        $session_wed_day      = (session('wed_day') === 1) ? 'Wed' : 0;
                        $session_thu_day      = (session('thu_day') === 1) ? 'Thu' : 0;
                        $session_fri_day      = (session('fri_day') === 1) ? 'Fri' : 0;
                        $session_sat_day      = (session('sat_day') === 1) ? 'Sat' : 0;
                        $session_sun_day      = (session('sun_day') === 1) ? 'Sun' : 0;

                        /* Getting bookings from booking collection status is 1=>Fix, 4=>Request, 7=>Inquiry */
                        $bookings  = Booking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabinname', session('cabin_name'))
                            ->whereIn('status', ['1', '4', '7'])
                            ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        /* Getting bookings from mschool collection status is 1=>Fix, 4=>Request, 7=>Inquiry */
                        $msBookings  = MountSchoolBooking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabin_name', session('cabin_name'))
                            ->whereIn('status', ['1', '4', '7'])
                            ->whereRaw(['check_in' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        /* Getting count of sleeps, beds and dorms */
                        if(count($bookings) > 0 || count($msBookings) > 0) {
                            $sleeps   = $bookings->sum('sleeps');
                            $beds     = $bookings->sum('beds');
                            $dorms    = $bookings->sum('dormitory');
                            $msSleeps = $msBookings->sum('sleeps');
                            $msBeds   = $msBookings->sum('beds');
                            $msDorms  = $msBookings->sum('dormitory');
                        }

                        /* Taking beds, dorms and sleeps depends up on sleeping_place */
                        if(session('sleeping_place') != 1) {

                            $totalBeds  = $beds + $msBeds;
                            $totalDorms = $dorms + $msDorms;

                            /* Calculating beds & dorms of regular and not regular booking */
                            if ($request->session()->has('regular') || $request->session()->has('not_regular')) {

                                if(session('not_regular') === 1) {

                                    $not_regular_date_explode = explode(" - ", session('not_regular_date'));
                                    $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                    $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                    $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                    foreach($generateNotRegularDates as $generateNotRegularDate) {
                                        $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                    }

                                    //print_r($not_regular_dates); //[2017-09-01 2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02, 2017-09-01  2017-09-02]
                                    //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]
                                    if(in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if(($totalBeds < session('not_regular_beds')) || ($totalDorms < session('not_regular_dorms'))) {

                                            $available_not_regular_beds = session('not_regular_beds') - $totalBeds;

                                            if($request->beds <= $available_not_regular_beds) {
                                                //print_r(' Not regular beds available '.' availableBeds ' . $available_not_regular_beds);
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                //print_r(' Not regular beds not available '.' availableBeds ' . $available_not_regular_beds);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                            }

                                            $available_not_regular_dorms = session('not_regular_dorms') - $totalDorms;

                                            if($request->dorms <= $available_not_regular_dorms) {
                                                //print_r(' Not regular dorms available '.' availableDorms ' . $available_not_regular_dorms);
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                //print_r(' Not regular dorms not available '.' availableDorms ' . $available_not_regular_dorms);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                            }
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' not_regular_beds: '.session('not_regular_beds').' totalBeds '. $totalBeds . ' not_regular_dorms: '.session('not_regular_dorms').' totalDorms '. $totalDorms);
                                    }

                                    //print_r($regular_dates_array); //2017-09-02, 2017-09-03


                                    /*session('not_regular_beds');
                                    session('not_regular_dorms');
                                    session('not_regular_sleeps');*/
                                }

                                if(session('regular') === 1) {

                                    if($session_mon_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('mon_beds')) || ($totalDorms < session('mon_dorms'))) {

                                                $available_mon_beds = session('mon_beds') - $totalBeds;

                                                if($request->beds <= $available_mon_beds) {
                                                    //print_r(' mon beds available '.' available_mon_beds ' . $available_mon_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r(' mon beds not available '.' available_mon_beds ' . $available_mon_beds);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_mon_dorms = session('mon_dorms') - $totalDorms;

                                                if($request->dorms <= $available_mon_dorms) {
                                                    //print_r(' mon dorms available '.' available_mon_dorms ' . $available_mon_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r(' mon dorms not available '.' available_mon_dorms ' . $available_mon_dorms);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' mon_beds: '.session('mon_beds').' totalBeds '. $totalBeds . ' mon_dorms: '.session('mon_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_tue_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('tue_beds')) || ($totalDorms < session('tue_dorms'))) {

                                                $available_tue_beds = session('tue_beds') - $totalBeds;

                                                if($request->beds <= $available_tue_beds) {
                                                    //print_r(' tue_beds available '.' available_tue_beds ' . $available_tue_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r(' tue_beds not available '.' available_tue_beds ' . $available_tue_beds);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_tue_dorms = session('tue_dorms') - $totalDorms;

                                                if($request->dorms <= $available_tue_dorms) {
                                                    //print_r(' tue_dorms available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r(' tue_dorms not available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' tue_beds: '.session('tue_beds').' totalBeds '. $totalBeds . ' tue_dorms: '.session('tue_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_wed_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('wed_beds')) || ($totalDorms < session('wed_dorms'))) {

                                                $available_wed_beds = session('wed_beds') - $totalBeds;

                                                if($request->beds <= $available_wed_beds) {
                                                    //print_r(' wed_beds available '.' available_wed_beds ' . $available_wed_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_wed_dorms = session('wed_dorms') - $totalDorms;

                                                if($request->dorms <= $available_wed_dorms) {
                                                    //print_r(' wed_dorms available '.' available_wed_dorms ' . $available_wed_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' wed_beds: '.session('wed_beds').' totalBeds '. $totalBeds . ' wed_dorms: '.session('wed_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_thu_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('thu_beds')) || ($totalDorms < session('thu_dorms'))) {

                                                $available_thu_beds = session('thu_beds') - $totalBeds;

                                                if($request->beds <= $available_thu_beds) {
                                                    //print_r(' thu_beds available '.' available_thu_beds ' . $available_thu_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_thu_dorms = session('thu_dorms') - $totalDorms;

                                                if($request->dorms <= $available_thu_dorms) {
                                                    //print_r(' thu_dorms available '.' available_thu_dorms ' . $available_thu_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' thu_beds: '.session('thu_beds').' totalBeds '. $totalBeds . ' thu_dorms: '.session('thu_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_fri_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('fri_beds')) || ($totalDorms < session('fri_dorms'))) {

                                                $available_fri_beds = session('fri_beds') - $totalBeds;

                                                if($request->beds <= $available_fri_beds) {
                                                    //print_r(' fri_beds available '.' available_fri_beds ' . $available_fri_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_fri_dorms = session('fri_dorms') - $totalDorms;

                                                if($request->dorms <= $available_fri_dorms) {
                                                    //print_r(' fri_dorms available '.' available_fri_dorms ' . $available_fri_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' fri_beds: '.session('fri_beds').' totalBeds '. $totalBeds . ' fri_dorms: '.session('fri_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_sat_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('sat_beds')) || ($totalDorms < session('sat_dorms'))) {

                                                $available_sat_beds = session('sat_beds') - $totalBeds;

                                                if($request->beds <= $available_sat_beds) {
                                                    //print_r(' sat_beds available '.' available_sat_beds ' . $available_sat_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_sat_dorms = session('sat_dorms') - $totalDorms;

                                                if($request->dorms <= $available_sat_dorms) {
                                                    //print_r(' sat_dorms available '.' available_sat_dorms ' . $available_sat_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' sat_beds: '.session('sat_beds').' totalBeds '. $totalBeds . ' sat_dorms: '.session('sat_dorms').' totalDorms '. $totalDorms);
                                        }

                                    }

                                    if($session_sun_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if(($totalBeds < session('sun_beds')) || ($totalDorms < session('sun_dorms'))) {

                                                $available_sun_beds = session('sun_beds') - $totalBeds;

                                                if($request->beds <= $available_sun_beds) {
                                                    //print_r(' sun_beds available '.' available_sun_beds ' . $available_sun_beds);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                                }

                                                $available_sun_dorms = session('sun_dorms') - $totalDorms;

                                                if($request->dorms <= $available_sun_dorms) {
                                                    //print_r(' sun_dorms available '.' available_sun_dorms ' . $available_sun_dorms);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' sun_beds: '.session('sun_beds').' totalBeds '. $totalBeds . ' sun_dorms: '.session('sun_dorms').' totalDorms '. $totalDorms );
                                        }

                                    }
                                }
                            }

                            /* Calculating beds & dorms of normal booking */
                            //print_r(array_unique($regular_dates_array)); //[2017-09-02, 2017-09-04] //if not regular has 2017-09-04 and regular has 2017-09-04

                            //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]

                            if(!in_array($generateBookingDat, $regular_dates_array)) {

                                if(($totalBeds < session('beds')) || ($totalDorms < session('dormitory'))) {

                                    $availableBeds = session('beds') - $totalBeds;

                                    if($request->beds <= $availableBeds) {
                                        //print_r(' Beds available '.' availableBeds ' . $availableBeds);
                                        $availableStatus[] = 'available';
                                    }
                                    else {
                                        $availableStatus[] = 'notAvailable';
                                        return response()->json(['error' => 'Beds not available on '.$generateBookingDat], 422);
                                    }

                                    $availableDorms = session('dormitory') - $totalDorms;

                                    if($request->dorms <= $availableDorms) {
                                        //print_r(' Dorms available '.' availableDorms ' . $availableDorms);
                                        $availableStatus[] = 'available';
                                    }
                                    else {
                                        $availableStatus[] = 'notAvailable';
                                        return response()->json(['error' => 'Dorms not available on '.$generateBookingDat], 422);
                                    }

                                }
                                else {
                                    $availableStatus[] = 'notAvailable';
                                    return response()->json(['error' => 'Beds and Dorms already filled on '.$generateBookingDat], 422);
                                }
                                //print_r(' Date '.$generateBookingDat.' beds: '.session('beds').' totalBeds '. $totalBeds . ' dormitory: '.session('dormitory').' totalDorms '. $totalDorms );
                            }

                            /*print_r(' bookBeds: '.$beds.' BookDorms: '.$dorms).'<br>';
                            print_r(' mschoolBeds: '.$msBeds.' mschoolDorms: '.$msDorms);
                            print_r(' totalBeds: '.$totalBeds.' totalDorms: '.$totalDorms);*/

                        }
                        else {
                            $totalSleeps     = $sleeps + $msSleeps;

                            /* Calculating sleeps of regular and not regular booking */
                            if ($request->session()->has('regular') || $request->session()->has('not_regular')) {

                                if(session('not_regular') === 1) {

                                    $not_regular_date_explode = explode(" - ", session('not_regular_date'));
                                    $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                    $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                    $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                    foreach($generateNotRegularDates as $generateNotRegularDate) {
                                        $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                    }

                                    if(in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if($totalSleeps < session('not_regular_sleeps')) {

                                            $available_not_regular_sleeps = session('not_regular_sleeps') - $totalSleeps;

                                            if($request->sleeps <= $available_not_regular_sleeps) {
                                                //print_r(' Not regular sleeps available '.' availableSleeps' . $available_not_regular_sleeps);
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                //print_r(' Not regular sleeps not available '.' availableSleeps' . $available_not_regular_sleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                            }

                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' not_regular_sleeps: '.session('not_regular_sleeps').' totalSleeps '. $totalSleeps);
                                    }

                                }

                                if(session('regular') === 1) {

                                    if($session_mon_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('mon_sleeps')) {

                                                $availableMonSleeps = session('mon_sleeps') - $totalSleeps;

                                                if($request->sleeps <= $availableMonSleeps) {
                                                    //print_r('Mon sleeps available' . ' availableMonSleeps' . $availableMonSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Mon sleeps not available'. ' availableMonSleeps' . $availableMonSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' mon_sleeps: '.session('mon_sleeps').' totalSleeps '. $totalSleeps);
                                        }

                                    }

                                    if($session_tue_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('tue_sleeps')) {

                                                $availableTueSleeps = session('tue_sleeps') - $totalSleeps;

                                                if($request->sleeps <= $availableTueSleeps) {
                                                    //print_r('Tue sleeps available' . ' availableTueSleeps' . $availableTueSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Tue sleeps not available' . ' availableTueSleeps' . $availableTueSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' tue_sleeps: '.session('tue_sleeps').' totalSleeps '. $totalSleeps);
                                        }

                                    }

                                    if($session_wed_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('wed_sleeps')) {

                                                $availableWedSleeps = session('wed_sleeps') - $totalSleeps;

                                                if($request->sleeps <= $availableWedSleeps) {
                                                    //print_r('Wed sleeps available'. ' availableWedSleeps ' . $availableWedSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Wed sleeps not available'. ' availableWedSleeps ' . $availableWedSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' wed_sleeps: '.session('wed_sleeps').' totalSleeps '. $totalSleeps);

                                        }


                                    }

                                    if($session_thu_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('thu_sleeps')) {

                                                $availableThuSleeps = session('thu_sleeps') - $totalSleeps;

                                                if($request->sleeps <= $availableThuSleeps) {
                                                    //print_r('Thu sleeps available'. ' availableThuSleeps ' . $availableThuSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Thu sleeps not available'. ' availableThuSleeps ' . $availableThuSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' thu_sleeps: '.session('thu_sleeps').' totalSleeps '. $totalSleeps);
                                        }
                                    }

                                    if($session_fri_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('fri_sleeps')) {

                                                $availableFriSleeps = session('fri_sleeps') - $totalSleeps;

                                                if($request->sleeps <= $availableFriSleeps) {
                                                    //print_r('Fri sleeps available' . ' availableFriSleeps ' . $availableFriSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Fri sleeps not available' . ' availableFriSleeps ' . $availableFriSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }

                                            //print_r(' Date '.$generateBookingDat.' fri_sleeps: '.session('fri_sleeps').' totalSleeps '. $totalSleeps);

                                        }


                                    }

                                    if($session_sat_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {

                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('sat_sleeps')) {

                                                $availableSatSleeps = session('sat_sleeps') - $totalSleeps;
                                                if($request->sleeps <= $availableSatSleeps) {
                                                    //print_r('Sat sleeps available' . ' availableSatSleeps ' . $availableSatSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Sat sleeps not available' . ' availableSatSleeps ' . $availableSatSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' sat_sleeps: '.session('sat_sleeps').' totalSleeps '. $totalSleeps);
                                        }


                                    }

                                    if($session_sun_day === $generateBookingDay) {

                                        if(!in_array($generateBookingDat, $not_regular_dates)) {
                                            $regular_dates_array[] = $generateBookingDat;

                                            if($totalSleeps < session('sun_sleeps')) {

                                                $availableSunSleeps = session('sun_sleeps') - $totalSleeps;
                                                if($request->sleeps <= $availableSunSleeps) {
                                                    //print_r('Sun sleeps available'. ' availableSunSleeps ' . $availableSunSleeps);
                                                    $availableStatus[] = 'available';
                                                }
                                                else {
                                                    //print_r('Sun sleeps not available'. ' availableSunSleeps ' . $availableSunSleeps);
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                                }

                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                            }
                                            //print_r(' Date '.$generateBookingDat.' sun_sleeps: '.session('sun_sleeps').' totalSleeps '. $totalSleeps );
                                        }

                                    }
                                }

                            }

                            /* Calculating sleeps of normal booking */
                            if(!in_array($generateBookingDat, $regular_dates_array)) {

                                if($totalSleeps < session('sleeps')) {

                                    $availableSleeps = session('sleeps') - $totalSleeps;

                                    if($request->sleeps <= $availableSleeps) {
                                        //print_r(' Sleeps available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' availableSleeps ' . $availableSleeps);
                                        $availableStatus[] = 'available';
                                    }
                                    else {
                                        //print_r(' Sleeps not available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' availableSleeps ' . $availableSleeps);
                                        $availableStatus[] = 'notAvailable';
                                        return response()->json(['error' => 'Sleeps not available on '.$generateBookingDat], 422);
                                    }

                                }
                                else {
                                    $availableStatus[] = 'notAvailable';
                                    return response()->json(['error' => 'Sleeps already filled on '.$generateBookingDat], 422);
                                }

                            }

                            /*print_r(' sleeps: '.$sleeps).'<br>';
                            print_r(' mschoolsleeps: '.$msSleeps);
                            print_r(' TotalSleeps: '.$totalSleeps);
                            print_r(' AvailableSleeps: '.$availableSleeps);*/
                        }
                    }
                    else {
                        return response()->json(['error' => 'Quota exceeded! Maximum 60 days you can book'], 422);
                    }
                    /* Checking bookings available end */
                }

                if(!in_array('notAvailable', $availableStatus)) {
                    $available = 'success';
                    session(['availableSuccess' => $available]);
                    session(['requestDorms' => $request->dorms]);
                    session(['requestBeds' => $request->beds]);
                    session(['requestSleeps' => $request->sleeps]);
                    session(['dateFrom' => $request->dateFrom]);
                    session(['dateTo' => $request->dateTo]);
                    //session()->flash('availableSuccess', $available);
                }
            }

        }

        return response()->json(['available' => $available]);
    }

    /**
     * Search available data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function calendarAvailability(Request $request)
    {
        $holiday_prepare    = [];
        $disableDates       = [];

        if($request->dateFrom != '') {
            $monthBegin        = $request->dateFrom;
            $monthEnd          = date('Y-m-t 23:59:59', strtotime($request->dateFrom));
            $seasons           = Season::where('cabin_owner', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
                ->where('cabin_id', new \MongoDB\BSON\ObjectID(session('cabin_id')))
                ->get();

            if($seasons) {

                $generateDates  = $this->generateDates($monthBegin, $monthEnd);

                foreach ($generateDates as $generateDate) {

                    $dates = $generateDate->format('Y-m-d');
                    $day   = $generateDate->format('D');

                    foreach($seasons as $season) {

                        if( ($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($dates >= ($season->earliest_summer_open)->format('Y-m-d')) && ($dates < ($season->latest_summer_close)->format('Y-m-d')) ) {
                            $holiday_prepare[] = ($season->summer_mon === 1) ? 'Mon' : 0;
                            $holiday_prepare[] = ($season->summer_tue === 1) ? 'Tue' : 0;
                            $holiday_prepare[] = ($season->summer_wed === 1) ? 'Wed' : 0;
                            $holiday_prepare[] = ($season->summer_thu === 1) ? 'Thu' : 0;
                            $holiday_prepare[] = ($season->summer_fri === 1) ? 'Fri' : 0;
                            $holiday_prepare[] = ($season->summer_sat === 1) ? 'Sat' : 0;
                            $holiday_prepare[] = ($season->summer_sun === 1) ? 'Sun' : 0;
                        }
                        elseif( ($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($dates >= ($season->earliest_winter_open)->format('Y-m-d')) && ($dates < ($season->latest_winter_close)->format('Y-m-d')) ) {
                            $holiday_prepare[] = ($season->winter_mon === 1) ? 'Mon' : 0;
                            $holiday_prepare[] = ($season->winter_tue === 1) ? 'Tue' : 0;
                            $holiday_prepare[] = ($season->winter_wed === 1) ? 'Wed' : 0;
                            $holiday_prepare[] = ($season->winter_thu === 1) ? 'Thu' : 0;
                            $holiday_prepare[] = ($season->winter_fri === 1) ? 'Fri' : 0;
                            $holiday_prepare[] = ($season->winter_sat === 1) ? 'Sat' : 0;
                            $holiday_prepare[] = ($season->winter_sun === 1) ? 'Sun' : 0;
                        }

                    }

                    $prepareArray           = [$dates => $day];
                    $array_unique           = array_unique($holiday_prepare);
                    $array_intersect        = array_intersect($prepareArray,$array_unique);

                    foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                        $disableDates[] = $array_intersect_key;
                    }
                }

            }
        }

        return response()->json(['disableDates' => $disableDates], 201);

    }
}
