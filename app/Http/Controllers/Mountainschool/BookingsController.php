<?php

namespace App\Http\Controllers\Mountainschool;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bmessages;
use App\Booking;
use App\MountSchoolBooking;
use App\Userlist;
use App\Cabin;
use App\Tour;
use App\Season;
use DateTime;
use DatePeriod;
use DateInterval;
use Auth;
use \App\Http\Requests\Mountainschool\BookingRequest as BookingRequest;
use Carbon\Carbon;
use App\Traits\DateGenerate;
use App\Traits\DateFormat;

class BookingsController extends Controller
{
    use DateGenerate, DateFormat;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mountainschool.bookings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tourList = Tour::where('is_delete', 0)
                    ->where('user_id', Auth::user()->_id)
                    ->orderBy('createdate', 'desc')
                    ->get();

        return view('mountainschool.newBooking', ['tourList' => $tourList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingRequest $request)
    {
        if (isset($request->formPart) && $request->formPart == 'newBooking') {
            $available             = 'failure';
            $bedsRequest           = 0;
            $dormsRequest          = 0;
            $requestBedsSumDorms   = 0;
            $sleepsRequest         = 0;
            $not_regular_dates     = [];
            $dates_array           = [];
            $availableStatus       = [];
            $invoiceNumber         = '';
            $holiday_prepare       = [];
            $test                  = [];

            $clickHere             = '<a href="/inquiry">click here</a>';
            for ($tb = 0; $tb < count($request->get('ind_tour_no')); $tb++) {
                for ($i = 1; $i <= $request->no_cabins; $i++) {
                    $cabinId             = 'cabinId' . $i;
                    $no_guides           = 'no_guides' . $i;
                    $guests              = 'guests' . $i;
                    $check_in            = 'check_in' . $i;
                    $check_out           = 'check_out' . $i;
                    $halfboard           = 'halfboard' . $i;
                    $dormitory           = 'dormitory' . $i;
                    $beds                = 'beds' . $i;
                    $sleeps              = 'sleeps' . $i;
                    $monthBegin          = DateTime::createFromFormat('d.m.y', $request->$check_in[$tb])->format('Y-m-d');
                    $monthEnd            = DateTime::createFromFormat('d.m.y', $request->$check_out[$tb])->format('Y-m-d');
                    $d1                  = new DateTime($monthBegin);
                    $d2                  = new DateTime($monthEnd);
                    $dateDifference      = $d2->diff($d1);
                    $week_days           = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

                    if($monthBegin < $monthEnd) {
                        if($dateDifference->days <= 60) {
                            // Cabin Details
                            $cabinDetails       = Cabin::where('is_delete', 0)
                                ->where('_id', new \MongoDB\BSON\ObjectID($request->$cabinId[$tb]))
                                ->first();

                            // If cabin is a registered cabin then booking data store in to database
                            if($cabinDetails->other_cabin === '0') {

                                // Generate auto number and create invoice number
                                if (!empty($cabinDetails->invoice_autonum)) {
                                    $autoNumber = (int)$cabinDetails->invoice_autonum + 1;

                                    $cabinDetails->invoice_autonum = $autoNumber;
                                    $cabinDetails->save();
                                }
                                else {
                                    $autoNumber = 100000;
                                }

                                if (!empty($cabinDetails->invoice_code)) {
                                    $invoiceCode   = $cabinDetails->invoice_code;
                                    $invoiceNumber = $invoiceCode . "-" . date("y") . "-" . $autoNumber;
                                }

                                $seasons           = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->$cabinId[$tb]))->get();

                                // Generate dates b/w checking from and to
                                $generateBookingDates = $this->generateDates($monthBegin, $monthEnd);

                                foreach ($generateBookingDates as $generateBookingDate) {

                                    $dates                 = $generateBookingDate->format('Y-m-d');
                                    $day                   = $generateBookingDate->format('D');
                                    $bookingDateSeasonType = null;

                                    /* Checking season begin */
                                    if($seasons) {
                                        foreach ($seasons as $season) {

                                            if (($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($dates >= ($season->earliest_summer_open)->format('Y-m-d')) && ($dates < ($season->latest_summer_close)->format('Y-m-d'))) {
                                                $holiday_prepare[]     = ($season->summer_mon === 1) ? 'Mon' : 0;
                                                $holiday_prepare[]     = ($season->summer_tue === 1) ? 'Tue' : 0;
                                                $holiday_prepare[]     = ($season->summer_wed === 1) ? 'Wed' : 0;
                                                $holiday_prepare[]     = ($season->summer_thu === 1) ? 'Thu' : 0;
                                                $holiday_prepare[]     = ($season->summer_fri === 1) ? 'Fri' : 0;
                                                $holiday_prepare[]     = ($season->summer_sat === 1) ? 'Sat' : 0;
                                                $holiday_prepare[]     = ($season->summer_sun === 1) ? 'Sun' : 0;
                                                $bookingDateSeasonType = 'summer';
                                            }
                                            elseif (($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($dates >= ($season->earliest_winter_open)->format('Y-m-d')) && ($dates < ($season->latest_winter_close)->format('Y-m-d'))) {
                                                $holiday_prepare[]     = ($season->winter_mon === 1) ? 'Mon' : 0;
                                                $holiday_prepare[]     = ($season->winter_tue === 1) ? 'Tue' : 0;
                                                $holiday_prepare[]     = ($season->winter_wed === 1) ? 'Wed' : 0;
                                                $holiday_prepare[]     = ($season->winter_thu === 1) ? 'Thu' : 0;
                                                $holiday_prepare[]     = ($season->winter_fri === 1) ? 'Fri' : 0;
                                                $holiday_prepare[]     = ($season->winter_sat === 1) ? 'Sat' : 0;
                                                $holiday_prepare[]     = ($season->winter_sun === 1) ? 'Sun' : 0;
                                                $bookingDateSeasonType = 'winter';
                                            }

                                        }

                                        if (!$bookingDateSeasonType)
                                        {
                                            return response()->json(['error' => __('tours.notSeasonTime')], 422);
                                        }

                                        $prepareArray       = [$dates => $day];
                                        $array_unique       = array_unique($holiday_prepare);
                                        $array_intersect    = array_intersect($prepareArray, $array_unique);

                                        foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                                            if((strtotime($array_intersect_key) >= strtotime($monthBegin)) && (strtotime($array_intersect_key) < strtotime($monthEnd))) {
                                                return response()->json(['error' => __('tours.holidayIncludedAlert')], 422);
                                            }
                                        }
                                    }
                                    /* Checking season end */

                                    //Getting bookings from booking collection status 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart 9=> Old (Booking updated)
                                    $bookings             = Booking::select('beds', 'dormitory', 'sleeps')
                                        ->where('is_delete', 0)
                                        ->where('cabinname', $cabinDetails->name)
                                        ->whereIn('status', ['1', '4', '5', '8'])
                                        ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                                        ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                                        ->get();

                                    // Getting bookings from mschool collection status 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart 9=> Old (Booking updated)
                                    $msBookings           = MountSchoolBooking::select('beds', 'dormitory', 'sleeps')
                                        ->where('is_delete', 0)
                                        ->where('cabin_name', $cabinDetails->name)
                                        ->whereIn('status', ['1', '4', '5', '8'])
                                        ->whereRaw(['check_in' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                                        ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                                        ->get();

                                    // Getting count of sleeps, beds and dorms
                                    if(count($bookings) > 0) {
                                        $sumSleeps        = $bookings->sum('sleeps');
                                        $sumBeds          = $bookings->sum('beds');
                                        $sumDorms         = $bookings->sum('dormitory');
                                    }
                                    else {
                                        $sumDorms         = 0;
                                        $sumBeds          = 0;
                                        $sumSleeps        = 0;
                                    }

                                    if(count($msBookings) > 0) {
                                        $msSumSleeps      = $msBookings->sum('sleeps');
                                        $msSumBeds        = $msBookings->sum('beds');
                                        $msSumDorms       = $msBookings->sum('dormitory');
                                    }
                                    else {
                                        $msSumSleeps      = 0;
                                        $msSumBeds        = 0;
                                        $msSumDorms       = 0;
                                    }

                                    // Taking beds, dorms and sleeps depends up on sleeping_place
                                    if($cabinDetails->sleeping_place != 1) {
                                        if( ((int)$request->$beds[$tb] + (int)$request->$dormitory[$tb]) === ((int)$request->$guests[$tb] + (int)$request->$no_guides[$tb]) )
                                        {
                                            $totalBeds           = $sumBeds + $msSumBeds;
                                            $totalDorms          = $sumDorms + $msSumDorms;

                                            $bedsRequest         = (int)$request->$beds[$tb];
                                            $dormsRequest        = (int)$request->$dormitory[$tb];
                                            $requestBedsSumDorms = (int)$request->$beds[$tb] + (int)$request->$dormitory[$tb];

                                            /* Calculating beds & dorms for not regular */
                                            if($cabinDetails->not_regular === 1) {
                                                $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                                $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                                $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date. We need to add time
                                                $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                                foreach($generateNotRegularDates as $generateNotRegularDate) {
                                                    $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                                }

                                                if(in_array($dates, $not_regular_dates)) {

                                                    $dates_array[] = $dates;

                                                    if(($totalBeds < $cabinDetails->not_regular_beds) || ($totalDorms < $cabinDetails->not_regular_dorms)) {
                                                        $not_regular_beds_diff              = $cabinDetails->not_regular_beds - $totalBeds;
                                                        $not_regular_dorms_diff             = $cabinDetails->not_regular_dorms - $totalDorms;

                                                        /* Available beds and dorms on not regular */
                                                        $not_regular_beds_avail             = ($not_regular_beds_diff >= 0) ? $not_regular_beds_diff : 0;
                                                        $not_regular_dorms_avail            = ($not_regular_dorms_diff >= 0) ? $not_regular_dorms_diff : 0;

                                                        if($bedsRequest <= $not_regular_beds_avail) {
                                                            $availableStatus[] = 'available';
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                        }

                                                        if($dormsRequest <= $not_regular_dorms_avail) {
                                                            $availableStatus[] = 'available';
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                        }

                                                        /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                        if($cabinDetails->not_regular_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->not_regular_inquiry_guest) {
                                                            $availableStatus[] = 'notAvailable';
                                                            /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->not_regular_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                            return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->not_regular_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                        }
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                    }
                                                }
                                            }

                                            /* Calculating beds & dorms for regular */
                                            if($cabinDetails->regular === 1) {
                                                foreach ($week_days as $week_day) {
                                                    $cabin_day = $week_day . '_day';

                                                    if ($cabinDetails->$cabin_day == 1 && ucfirst($week_day) == $day) {

                                                        if(!in_array($dates, $dates_array)) {

                                                            $dates_array[] = $dates;

                                                            if(($totalBeds < $cabinDetails->$week_day . '_beds') || ($totalDorms < $cabinDetails->$week_day . '_dorms')) {
                                                                $beds_diff              = $cabinDetails->$week_day . '_beds' - $totalBeds;
                                                                $dorms_diff             = $cabinDetails->$week_day . '_dorms' - $totalDorms;

                                                                /* Available beds and dorms on regular monday */
                                                                $beds_avail             = ($beds_diff >= 0) ? $beds_diff : 0;
                                                                $dorms_avail            = ($dorms_diff >= 0) ? $dorms_diff : 0;

                                                                if ($bedsRequest <= $beds_avail) {
                                                                    $availableStatus[] = 'available';
                                                                }
                                                                else {
                                                                    $availableStatus[] = 'notAvailable';
                                                                    return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                                }

                                                                if($dormsRequest <= $dorms_avail) {
                                                                    $availableStatus[] = 'available';
                                                                }
                                                                else {
                                                                    $availableStatus[] = 'notAvailable';
                                                                    return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                                }

                                                                /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                                if($cabinDetails->$week_day . '_inquiry_guest' > 0 && $requestBedsSumDorms >= $cabinDetails->$week_day . '_inquiry_guest') {
                                                                    $availableStatus[] = 'notAvailable';
                                                                    /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->$week_day . '_inquiry_guest'.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                    return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->mon_inquiry_guest - 1).__("tours.bookingLimitReachedTwo"), 'bookingOrder' => $i], 422);
                                                                }
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            /* Calculating beds & dorms for normal */
                                            if(!in_array($dates, $dates_array)) {
                                                if(($totalBeds < $cabinDetails->beds) || ($totalDorms < $cabinDetails->dormitory)) {

                                                    $normal_beds_diff              = $cabinDetails->beds - $totalBeds;
                                                    $normal_dorms_diff             = $cabinDetails->dormitory - $totalDorms;

                                                    /* Available beds and dorms on normal */
                                                    $normal_beds_avail             = ($normal_beds_diff >= 0) ? $normal_beds_diff : 0;
                                                    $normal_dorms_avail            = ($normal_dorms_diff >= 0) ? $normal_dorms_diff : 0;

                                                    if($bedsRequest <= $normal_beds_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                    }

                                                    if($dormsRequest <= $normal_dorms_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                    }

                                                    /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                    if($cabinDetails->inquiry_starts > 0 && $requestBedsSumDorms >= $cabinDetails->inquiry_starts) {
                                                        $availableStatus[] = 'notAvailable';
                                                        /*return response()->json(['error' =>  __("tours.inquiryAlert").$generateBookingDate->format("d.m"). __("tours.inquiryAlert1").$cabinDetails->inquiry_starts. __("tours.inquiryAlert2").$clickHere. __("tours.inquiryAlert3")], 422);*/
                                                        return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->inquiry_starts - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                    }
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' =>  __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                }
                                            }

                                        }
                                        else {
                                            return response()->json(['error' => __("tours.bedsDormsNotMatchGuestGuide"), 'bookingOrder' => $i], 422);
                                        }
                                    }
                                    else {
                                        if( (int)$request->$sleeps[$tb] === ((int)$request->$guests[$tb] + (int)$request->$no_guides[$tb]) ) {
                                            $totalSleeps         = $sumSleeps + $msSumSleeps;

                                            $sleepsRequest       = (int)$request->$sleeps[$tb];
                                            $requestBedsSumDorms = (int)$request->$beds[$tb] + (int)$request->$dormitory[$tb];

                                            /* Calculating sleeps for not regular */
                                            if($cabinDetails->not_regular === 1) {
                                                $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                                $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                                $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                                $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                                foreach($generateNotRegularDates as $generateNotRegularDate) {
                                                    $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                                }

                                                if(in_array($dates, $not_regular_dates)) {

                                                    $dates_array[] = $dates;

                                                    if(($totalSleeps < $cabinDetails->not_regular_sleeps)) {
                                                        $not_regular_sleeps_diff       = $cabinDetails->not_regular_sleeps - $totalSleeps;

                                                        /* Available sleeps on not regular */
                                                        $not_regular_sleeps_avail      = ($not_regular_sleeps_diff >= 0) ? $not_regular_sleeps_diff : 0;

                                                        if($sleepsRequest <= $not_regular_sleeps_avail) {
                                                            $availableStatus[] = 'available';
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                        }

                                                        /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                        if($cabinDetails->not_regular_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->not_regular_inquiry_guest) {
                                                            $availableStatus[] = 'notAvailable';
                                                            /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->not_regular_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                            return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->not_regular_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                        }
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                    }
                                                }
                                            }

                                            /* Calculating sleeps for regular */
                                            if($cabinDetails->regular === 1) {

                                                foreach ($week_days as $week_day) {
                                                    $cabin_day = $week_day . '_day';

                                                    if ($cabinDetails->$cabin_day == 1 && ucfirst($week_day) == $day) {

                                                        if(!in_array($dates, $dates_array)) {

                                                            $dates_array[] = $dates;

                                                            if(($totalSleeps < $cabinDetails->$week_day . '_sleeps')) {
                                                                $sleeps_diff       = $cabinDetails->$week_day . '_sleeps' - $totalSleeps;

                                                                /* Available sleeps on regular monday */
                                                                $sleeps_avail      = ($sleeps_diff >= 0) ? $sleeps_diff : 0;

                                                                if($sleepsRequest <= $sleeps_avail) {
                                                                    $availableStatus[] = 'available';
                                                                }
                                                                else {
                                                                    $availableStatus[] = 'notAvailable';
                                                                    return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                                }

                                                                /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                                if($cabinDetails->$week_day . '_inquiry_guest' > 0 && $sleepsRequest >= $cabinDetails->$week_day . '_inquiry_guest') {
                                                                    $availableStatus[] = 'notAvailable';

                                                                    return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->mon_inquiry_guest - 1).__("tours.bookingLimitReachedTwo"), 'bookingOrder' => $i], 422);
                                                                }
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                            }
                                                        }
                                                    }
                                                }

                                            }

                                            /* Calculating sleeps for normal */
                                            if(!in_array($dates, $dates_array)) {

                                                if(($totalSleeps < $cabinDetails->sleeps)) {
                                                    $normal_sleeps_diff       = $cabinDetails->sleeps - $totalSleeps;

                                                    /* Available sleeps on normal */
                                                    $normal_sleeps_avail      = ($normal_sleeps_diff >= 0) ? $normal_sleeps_diff : 0;

                                                    if($sleepsRequest <= $normal_sleeps_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                    }

                                                    /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                    if($cabinDetails->inquiry_starts > 0 && $sleepsRequest >= $cabinDetails->inquiry_starts) {
                                                        $availableStatus[] = 'notAvailable';
                                                        /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->inquiry_starts.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                        return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->inquiry_starts - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                    }
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';
                                                    return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m"), 'bookingOrder' => $i], 422);
                                                }

                                            }
                                        }
                                        else {
                                            return response()->json(['error' => __("tours.sleepsNotMatchGuestGuide"), 'bookingOrder' => $i], 422);
                                        }
                                    }
                                }

                                //return $request->all();
                                $tour = Tour::where('_id', new \MongoDB\BSON\ObjectID($request->tourname))->first();

                                // Save Booking Data
                                $booking = new MountSchoolBooking;

                                $booking->tour_name      = $tour['tour_name'];
                                $booking->ind_tour_no    = $request->ind_tour_no[0];
                                $booking->no_guides      = $request->$no_guides[0];
                                $booking->total_guests   = $request->$guests[0] + $request->$no_guides[0];
                                $booking->guests         = $request->$guests[0];
                                $booking->tour_guide     = $request->tour_guide;
                                $booking->ind_notice     = $request->ind_notice;
                                $booking->cabin_name     = $cabinDetails->name;
                                $booking->check_in       = DateTime::createFromFormat('d.m.y', $request->$check_in[0])->format('Y-m-d');
                                $booking->reserve_to     = DateTime::createFromFormat('d.m.y', $request->$check_out[0])->format('Y-m-d');
                                $booking->user_id        = new \MongoDB\BSON\ObjectID(Auth::id());
                                $booking->bookingdate    = Carbon::now();
                                $booking->invoice_number = $invoiceNumber;
                                $booking->is_delete      = 0;
                                $booking->status         = "1";
                                $booking->sleeps         = $request->$sleeps[0];
                                $booking->beds           = $request->$beds[0];
                                $booking->dormitory      = $request->$dormitory[0];

                                $booking->save();
                            }
                        }
                        else {
                            return response()->json(['error' => __("tours.sixtyDaysExceed"), 'bookingOrder' => $i], 422);
                        }
                    }
                    else {
                        //return response()->json(['failureMsg' =>  __('tours.dateGreater')]);
                        return response()->json(['error' => __("tours.dateGreater"), 'bookingOrder' => $i], 422);
                    }
                }
            }

            // Successfully save a booking
            $available = 'success';
            return response()->json(['response' => $available]);
        }
        else {
            return response()->json(['failureMsg' =>  __('tours.failureMsgBooking')]);
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
        $booking = MountSchoolBooking::find($id);
        $cabins  = Cabin::get();
        $cabin   = Cabin::where('name', $booking->cabin_name)->first();

        return view('mountainschool.bookings.edit', [
            'booking' => $booking,
            'cabins'  => $cabins,
            'cabin'   => $cabin
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingRequest $request, $id)
    {
        $booking = MountSchoolBooking::where('_id', new \MongoDB\BSON\ObjectID($id))->first();
        $cabin   = Cabin::where('name', $booking->cabin_name)->first();

        // Check if sleeps are available at given dates
        $error = $this->checkAvailableReservationsAndDates($request, $cabin);

        if ($error) {
            return back()->with('error', $error['error']);
        }

        $booking->handleRequest($request, $cabin);

        return redirect()->route('mountainschool.bookings')->with('message', __('mountainschool/bookings.notice.update'));
    }

    /**
     * Cancel the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $booking = MountSchoolBooking::where('_id', new \MongoDB\BSON\ObjectID($id))->first();

        $booking->handleCancelRequest();

        return redirect()->route('mountainschool.bookings')->with('message', __('mountainschool/bookings.notice.cancel'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params        = $request->all();
        $columns       = array(
            1  => 'invoice_number',
            2  => 'ind_tour_no',
            3  => 'cabin_name',
            4  => 'check_in',
            5  => 'reserve_to',
            6  => 'beds',
            7  => 'dormitory',
            8  => 'sleeps',
            9  => 'status',
            10 => 'edit'
        );

        $totalData     = MountSchoolBooking::where('is_delete', 0)
            ->where('user_id',  new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->count();

        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $q             = MountSchoolBooking::where('is_delete', 0)
            ->where('user_id',  new \MongoDB\BSON\ObjectID(Auth::id()));

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');

            $q->where(function($query) use ($search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('ind_tour_no', 'like', "%{$search}%");
            });

            $totalFiltered = $q->where(function($query) use ($search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('ind_tour_no', 'like', "%{$search}%");
            })
                ->count();
        }

        /* Date range func begin */
        if($request->input('is_date_search') == 'yes')
        {
            $checkin_from           = explode("-", $request->input('daterange'));
            $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
            $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);

            $q->whereBetween('check_in', [$dateBegin, $dateEnd]);

            $totalFiltered = $q->whereBetween('check_in', [$dateBegin, $dateEnd])
                ->count();
        }
        /* Date range func end */

        /* tfoot search functionality for booking number, email, status begin */
        if( !empty($params['columns'][1]['search']['value']))
        {
            $q->where(function($query) use ($params) {
                $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('invoice_number', 'like', "%{$params['columns'][1]['search']['value']}%");
            })
                ->count();
        }

        if( !empty($params['columns'][2]['search']['value']))
        {
            $q->where(function($query) use ($params) {
                $query->where('ind_tour_no', 'like', "%{$params['columns'][2]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('ind_tour_no', 'like', "%{$params['columns'][2]['search']['value']}%");
            })
                ->count();
        }

        if( !empty($params['columns'][3]['search']['value']))
        {
            $q->where(function($query) use ($params) {
                $query->where('cabin_name', 'like', "%{$params['columns'][3]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('cabin_name', 'like', "%{$params['columns'][3]['search']['value']}%");
            })
                ->count();
        }

        if( isset($params['columns'][8]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][8]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('status', "{$params['columns'][8]['search']['value']}");
            })
                ->count();
        }
        /* tfoot search functionality for booking number, email, status end */

        $bookings      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        $data          = array();
        $noData        = '<span class="label label-default">'.__("mountainschool.noResult").'</span>';

        if(!empty($bookings)) {
            foreach ($bookings as $key => $booking) {

                /* Condition for booking status */
                if($booking->status == '1') {
                    $bookingStatustxt = __("mountainschool.bookingFix");
                    $spanClass = "label-success";

                }
                else if ($booking->status == '2') {

                    $bookingStatustxt = __("mountainschool.cancelled");
                    $spanClass = "label-danger";
                }
                else if ($booking->status == '3') {

                    $bookingStatustxt = __("mountainschool.completed");
                    $spanClass = "label-primary";
                }
                else if ($booking->status == '4') {

                    $bookingStatustxt = __("mountainschool.request");
                    $spanClass = "label-info";
                }
                else if ($booking->status == '5') {
                    $bookingStatustxt = __("mountainschool.bookingWaiting");
                    $spanClass = "label-warning";
                }
                else {
                    $bookingStatustxt = __("mountainschool.noResult");
                    $spanClass = "label-default";

                }
                $bookingStatusLabel = '<span class="label '.$spanClass.'">'. $bookingStatustxt .'</span>';
                /* Checking check_in, reserve_to and booking date fields are available or not */
                if(!$booking->check_in){
                    $checkin_from = $noData;
                }
                else {
                    $checkin_from = ($booking->check_in)->format('d.m.y');
                }

                if(!$booking->reserve_to){
                    $reserve_to = $noData;
                }
                else {
                    $reserve_to = ($booking->reserve_to)->format('d.m.y');
                }


                /* Condition for beds, dorms and sleeps */
                if(empty($booking->beds) && empty($booking->dormitory))
                {
                    $sleeps    = $booking->sleeps;
                }
                else {
                    $beds      = $booking->beds;
                    $dormitory = $booking->dormitory;
                    $sleeps    = '----';
                }
                if(empty($booking->beds)){
                    $beds      = '----';
                }
                if(empty($booking->dormitory)){
                    $dormitory = '----';
                }

                $ind_tour_no                 = $booking->ind_tour_no;
                $booking->checkin_from       = $checkin_from;
                $booking->checkin_to         = $reserve_to;
                $booking->bookingStatusLabel = $bookingStatustxt;

                // Modal for booking details
                $details_view                = view('mountainschool.msbookingdetailspopup', ['booking' => $booking]);
                $details_contents            = (string) $details_view;

                $details_modal               = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_' . $booking->_id . '">' . $booking->invoice_number . '</a><div class="modal fade" id="bookingModal_' . $booking->_id . '" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content">' . $details_contents . '</div></div></div>';

                // Edit booking
                if ($booking->status != "2") {
                    $edit_section                  = '<a class="nounderline" href=" ' . route('mountainschool.bookings.edit', ['id' => $booking->_id]) . ' "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a class="nounderline m-l-10 text-danger" href=" ' . route('mountainschool.bookings.cancel', ['id' => $booking->_id]) . ' "><i class="fa fa-ban" aria-hidden="true"></i></a>';
                } else {
                    $edit_section                  = '<a class="nounderline" href=" ' . route('mountainschool.bookings.edit', ['id' => $booking->_id]) . ' "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                }

                // Data table contents
                $invoiceNumber_comment        = $details_modal;
                $checkbox                     = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                $nestedData['hash']           = $checkbox;
                $nestedData['invoice_number'] = $invoiceNumber_comment;
                $nestedData['ind_tour_no']    = $ind_tour_no;
                $nestedData['cabin_name']     = $booking->cabin_name;
                $nestedData['check_in']       = $checkin_from;
                $nestedData['reserve_to']     = $reserve_to;
                $nestedData['beds']           = $beds;
                $nestedData['dormitory']      = $dormitory;
                $nestedData['sleeps']         = $sleeps;
                $nestedData['status']         = $bookingStatusLabel;
                $nestedData['edit']           = $edit_section;
                $data[]                       = $nestedData;
            }

            $json_data = array(
                'draw'            => (int)$params['draw'],
                'recordsTotal'    => (int)$totalData,
                'recordsFiltered' => (int)$totalFiltered,
                'data'            => $data
            );

            return response()->json($json_data);
        }
    }

    public function checkAvailableReservationsAndDates($request, $cabin)
    {
        $dates_array         = [];
        $monthBegin          = DateTime::createFromFormat('d.m.y', $request->check_in1[0])->format('Y-m-d');
        $monthEnd            = DateTime::createFromFormat('d.m.y', $request->check_out1[0])->format('Y-m-d');
        $d1                  = new DateTime($monthBegin);
        $d2                  = new DateTime($monthEnd);
        $dateDifference      = $d2->diff($d1);
        $week_days           = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

        if($monthBegin < $monthEnd) {
            if($dateDifference->days <= 60) {
                // Cabin Details
                $cabinDetails       = Cabin::where('is_delete', 0)
                    ->where('_id', new \MongoDB\BSON\ObjectID($cabin->_id))
                    ->first();

                // If cabin is a registered cabin then booking data store in to database
                if($cabinDetails->other_cabin === '0') {

                    // Generate auto number and create invoice number
                    if (!empty($cabinDetails->invoice_autonum)) {
                        $autoNumber = (int)$cabinDetails->invoice_autonum + 1;

                        $cabinDetails->invoice_autonum = $autoNumber;
                        $cabinDetails->save();
                    }
                    else {
                        $autoNumber = 100000;
                    }

                    if (!empty($cabinDetails->invoice_code)) {
                        $invoiceCode   = $cabinDetails->invoice_code;
                        $invoiceNumber = $invoiceCode . "-" . date("y") . "-" . $autoNumber;
                    }

                    $seasons           = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($cabin->_id))->get();

                    // Generate dates b/w checking from and to
                    $generateBookingDates = $this->generateDates($monthBegin, $monthEnd);

                    foreach ($generateBookingDates as $generateBookingDate) {

                        $dates                 = $generateBookingDate->format('Y-m-d');
                        $day                   = $generateBookingDate->format('D');
                        $bookingDateSeasonType = null;

                        /* Checking season begin */
                        if($seasons) {
                            foreach ($seasons as $season) {

                                if (($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($dates >= ($season->earliest_summer_open)->format('Y-m-d')) && ($dates < ($season->latest_summer_close)->format('Y-m-d'))) {
                                    $holiday_prepare[]     = ($season->summer_mon === 1) ? 'Mon' : 0;
                                    $holiday_prepare[]     = ($season->summer_tue === 1) ? 'Tue' : 0;
                                    $holiday_prepare[]     = ($season->summer_wed === 1) ? 'Wed' : 0;
                                    $holiday_prepare[]     = ($season->summer_thu === 1) ? 'Thu' : 0;
                                    $holiday_prepare[]     = ($season->summer_fri === 1) ? 'Fri' : 0;
                                    $holiday_prepare[]     = ($season->summer_sat === 1) ? 'Sat' : 0;
                                    $holiday_prepare[]     = ($season->summer_sun === 1) ? 'Sun' : 0;
                                    $bookingDateSeasonType = 'summer';
                                }
                                elseif (($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($dates >= ($season->earliest_winter_open)->format('Y-m-d')) && ($dates < ($season->latest_winter_close)->format('Y-m-d'))) {
                                    $holiday_prepare[]     = ($season->winter_mon === 1) ? 'Mon' : 0;
                                    $holiday_prepare[]     = ($season->winter_tue === 1) ? 'Tue' : 0;
                                    $holiday_prepare[]     = ($season->winter_wed === 1) ? 'Wed' : 0;
                                    $holiday_prepare[]     = ($season->winter_thu === 1) ? 'Thu' : 0;
                                    $holiday_prepare[]     = ($season->winter_fri === 1) ? 'Fri' : 0;
                                    $holiday_prepare[]     = ($season->winter_sat === 1) ? 'Sat' : 0;
                                    $holiday_prepare[]     = ($season->winter_sun === 1) ? 'Sun' : 0;
                                    $bookingDateSeasonType = 'winter';
                                }
                            }

                            if (!$bookingDateSeasonType)
                            {
                                return back()->with('error', __('tours.notSeasonTime'));
                            }

                            $prepareArray       = [$dates => $day];
                            $array_unique       = array_unique($holiday_prepare);
                            $array_intersect    = array_intersect($prepareArray, $array_unique);

                            foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                                if((strtotime($array_intersect_key) >= strtotime($monthBegin)) && (strtotime($array_intersect_key) < strtotime($monthEnd))) {
                                    return back()->with('error', __('tours.holidayIncludedAlert'));
                                }
                            }
                        }
                        /* Checking season end */

                        //Getting bookings from booking collection status 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart 9=> Old (Booking updated)
                        $bookings             = Booking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabinname', $cabinDetails->name)
                            ->whereIn('status', ['1', '4', '5', '8'])
                            ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        // Getting bookings from mschool collection status 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart 9=> Old (Booking updated)
                        $msBookings           = MountSchoolBooking::select('beds', 'dormitory', 'sleeps')
                            ->where('is_delete', 0)
                            ->where('cabin_name', $cabinDetails->name)
                            ->whereIn('status', ['1', '4', '5', '8'])
                            ->whereRaw(['check_in' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                            ->get();

                        // Getting count of sleeps, beds and dorms
                        if(count($bookings) > 0) {
                            $sumSleeps        = $bookings->sum('sleeps');
                            $sumBeds          = $bookings->sum('beds');
                            $sumDorms         = $bookings->sum('dormitory');
                        }
                        else {
                            $sumDorms         = 0;
                            $sumBeds          = 0;
                            $sumSleeps        = 0;
                        }

                        if(count($msBookings) > 0) {
                            $msSumSleeps      = $msBookings->sum('sleeps');
                            $msSumBeds        = $msBookings->sum('beds');
                            $msSumDorms       = $msBookings->sum('dormitory');
                        }
                        else {
                            $msSumSleeps      = 0;
                            $msSumBeds        = 0;
                            $msSumDorms       = 0;
                        }

                        // Taking beds, dorms and sleeps depends up on sleeping_place
                        if($cabinDetails->sleeping_place != 1) {
                            if( ((int)$request->beds + (int)$request->dorms) === ((int)$request->guests + (int)$request->guides) )
                            {
                                $totalBeds           = $sumBeds + $msSumBeds;
                                $totalDorms          = $sumDorms + $msSumDorms;

                                $bedsRequest         = (int)$request->beds;
                                $dormsRequest        = (int)$request->dorms;
                                $requestBedsSumDorms = (int)$request->beds + (int)$request->dorms;

                                /* Calculating beds & dorms for not regular */
                                if($cabinDetails->not_regular === 1) {
                                    $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                    $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                    $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date. We need to add time
                                    $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                    foreach($generateNotRegularDates as $generateNotRegularDate) {
                                        $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                    }

                                    if(in_array($dates, $not_regular_dates)) {

                                        $dates_array[] = $dates;

                                        if(($totalBeds < $cabinDetails->not_regular_beds) || ($totalDorms < $cabinDetails->not_regular_dorms)) {
                                            $not_regular_beds_diff              = $cabinDetails->not_regular_beds - $totalBeds;
                                            $not_regular_dorms_diff             = $cabinDetails->not_regular_dorms - $totalDorms;

                                            /* Available beds and dorms on not regular */
                                            $not_regular_beds_avail             = ($not_regular_beds_diff >= 0) ? $not_regular_beds_diff : 0;
                                            $not_regular_dorms_avail            = ($not_regular_dorms_diff >= 0) ? $not_regular_dorms_diff : 0;

                                            if($bedsRequest <= $not_regular_beds_avail) {
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return ['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")];
                                            }

                                            if($dormsRequest <= $not_regular_dorms_avail) {
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return ['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")];
                                            }

                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                            if($cabinDetails->not_regular_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->not_regular_inquiry_guest) {
                                                $availableStatus[] = 'notAvailable';

                                                return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->not_regular_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")];
                                            }
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';

                                            return ['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")];
                                        }
                                    }
                                }

                                /* Calculating beds & dorms for regular */
                                if($cabinDetails->regular === 1) {
                                    foreach ($week_days as $week_day) {
                                        $cabin_day = $week_day . '_day';

                                        if ($cabinDetails->$cabin_day == 1 && ucfirst($week_day) == $day) {
                                            if(!in_array($dates, $dates_array)) {

                                                $dates_array[] = $dates;

                                                if(($totalBeds < $cabinDetails->$week_day . '_beds') || ($totalDorms < $cabinDetails->$week_day . '_dorms')) {
                                                    $beds_diff              = $cabinDetails->$week_day . '_beds' - $totalBeds;
                                                    $dorms_diff             = $cabinDetails->$week_day . '_dorms' - $totalDorms;

                                                    /* Available beds and dorms on regular tuesday */
                                                    $beds_avail             = ($beds_diff >= 0) ? $beds_diff : 0;
                                                    $dorms_avail            = ($dorms_diff >= 0) ? $dorms_diff : 0;

                                                    if($bedsRequest <= $beds_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return ['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")];
                                                    }

                                                    if($dormsRequest <= $dorms_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return ['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")];
                                                    }

                                                    /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                    if($cabinDetails->$week_day . '_inquiry_guest' > 0 && $requestBedsSumDorms >= $cabinDetails->$week_day . '_inquiry_guest') {
                                                        $availableStatus[] = 'notAvailable';

                                                        return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->tue_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")];
                                                    }
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';

                                                    return ['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")];
                                                }
                                            }
                                        }
                                    }
                                }

                                /* Calculating beds & dorms for normal */
                                if(!in_array($dates, $dates_array)) {
                                    if(($totalBeds < $cabinDetails->beds) || ($totalDorms < $cabinDetails->dormitory)) {

                                        $normal_beds_diff              = $cabinDetails->beds - $totalBeds;
                                        $normal_dorms_diff             = $cabinDetails->dormitory - $totalDorms;

                                        /* Available beds and dorms on normal */
                                        $normal_beds_avail             = ($normal_beds_diff >= 0) ? $normal_beds_diff : 0;
                                        $normal_dorms_avail            = ($normal_dorms_diff >= 0) ? $normal_dorms_diff : 0;

                                        if($bedsRequest <= $normal_beds_avail) {
                                            $availableStatus[] = 'available';
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';
                                            return ['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")];
                                        }

                                        if($dormsRequest <= $normal_dorms_avail) {
                                            $availableStatus[] = 'available';
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';
                                            return ['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")];
                                        }

                                        /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                        if($cabinDetails->inquiry_starts > 0 && $requestBedsSumDorms >= $cabinDetails->inquiry_starts) {
                                            $availableStatus[] = 'notAvailable';

                                            return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->inquiry_starts - 1).__("tours.bookingLimitReachedTwo")];
                                        }
                                    }
                                    else {
                                        $availableStatus[] = 'notAvailable';
                                        return ['error' =>  __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")];
                                    }
                                }

                            }
                            else {
                                return ['error' => __("tours.bedsDormsNotMatchGuestGuide")];
                            }
                        }
                        else {
                            if( (int)$request->sleeps === ((int)$request->guests + (int)$request->guides) ) {
                                $totalSleeps         = $sumSleeps + $msSumSleeps;

                                $sleepsRequest       = (int)$request->sleeps;
                                $requestBedsSumDorms = (int)$request->beds + (int)$request->dorms;

                                /* Calculating sleeps for not regular */
                                if($cabinDetails->not_regular === 1) {
                                    $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                    $not_regular_date_begin   = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                    $not_regular_date_end     = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                    $generateNotRegularDates  = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                    foreach($generateNotRegularDates as $generateNotRegularDate) {
                                        $not_regular_dates[]  = $generateNotRegularDate->format('Y-m-d');
                                    }

                                    if(in_array($dates, $not_regular_dates)) {

                                        $dates_array[] = $dates;

                                        if(($totalSleeps < $cabinDetails->not_regular_sleeps)) {
                                            $not_regular_sleeps_diff       = $cabinDetails->not_regular_sleeps - $totalSleeps;

                                            /* Available sleeps on not regular */
                                            $not_regular_sleeps_avail      = ($not_regular_sleeps_diff >= 0) ? $not_regular_sleeps_diff : 0;

                                            if($sleepsRequest <= $not_regular_sleeps_avail) {
                                                $availableStatus[] = 'available';
                                            }
                                            else {
                                                $availableStatus[] = 'notAvailable';
                                                return ['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")];
                                            }

                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                            if($cabinDetails->not_regular_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->not_regular_inquiry_guest) {
                                                $availableStatus[] = 'notAvailable';

                                                return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->not_regular_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")];
                                            }
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';

                                            return ['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")];
                                        }
                                    }
                                }

                                /* Calculating sleeps for regular */
                                if($cabinDetails->regular === 1) {

                                    foreach ($week_days as $week_day) {
                                        $cabin_day = $week_day . '_day';

                                        if ($cabinDetails->$cabin_day == 1 && ucfirst($week_day) == $day) {

                                            if(!in_array($dates, $dates_array)) {

                                                $dates_array[] = $dates;

                                                if(($totalSleeps < $cabinDetails->$week_day . '_sleeps')) {
                                                    $sleeps_diff       = $cabinDetails->$week_day . '_sleeps' - $totalSleeps;

                                                    /* Available sleeps on regular monday */
                                                    $sleeps_avail      = ($sleeps_diff >= 0) ? $sleeps_diff : 0;

                                                    if($sleepsRequest <= $sleeps_avail) {
                                                        $availableStatus[] = 'available';
                                                    }
                                                    else {
                                                        $availableStatus[] = 'notAvailable';
                                                        return ['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")];
                                                    }

                                                    /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                    if($cabinDetails->$week_day . '_inquiry_guest' > 0 && $sleepsRequest >= $cabinDetails->$week_day . '_inquiry_guest') {
                                                        $availableStatus[] = 'notAvailable';

                                                        return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->mon_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")];
                                                    }
                                                }
                                                else {
                                                    $availableStatus[] = 'notAvailable';

                                                    return ['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")];
                                                }
                                            }
                                        }
                                    }
                                }

                                /* Calculating sleeps for normal */
                                if(!in_array($dates, $dates_array)) {

                                    if(($totalSleeps < $cabinDetails->sleeps)) {
                                        $normal_sleeps_diff       = $cabinDetails->sleeps - $totalSleeps;

                                        /* Available sleeps on normal */
                                        $normal_sleeps_avail      = ($normal_sleeps_diff >= 0) ? $normal_sleeps_diff : 0;

                                        if($sleepsRequest <= $normal_sleeps_avail) {
                                            $availableStatus[] = 'available';
                                        }
                                        else {
                                            $availableStatus[] = 'notAvailable';
                                            return ['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")];
                                        }

                                        /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                        if($cabinDetails->inquiry_starts > 0 && $sleepsRequest >= $cabinDetails->inquiry_starts) {
                                            $availableStatus[] = 'notAvailable';

                                            return ['error' => __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->inquiry_starts - 1).__("tours.bookingLimitReachedTwo")];
                                        }
                                    }
                                    else {
                                        $availableStatus[] = 'notAvailable';

                                        return ['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")];
                                    }

                                }
                            }
                            else {
                                return ['error' => __("tours.sleepsNotMatchGuestGuide")];
                            }
                        }
                    }
                }
            }
            else {
                return ['error' => __("tours.sixtyDaysExceed")];
            }
        }
        else {
            return ['error' => __("tours.dateGreater")];
        }
    }
}
