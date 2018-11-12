<?php

namespace App\Http\Controllers\Mountainschool;

use Illuminate\Http\Request;
use App\Http\Requests\TourRequest;
use App\Http\Controllers\Controller;
use App\Tour;
use App\Userlist;
use App\Settings;
use App\Cabin;
use App\Season;
use App\MountSchoolBooking;
use App\Booking;
use DateTime;
use DatePeriod;
use DateInterval;
use Auth;
use Carbon\Carbon;

class TourController extends Controller
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
        return view('mountainschool.tours');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params           = $request->all();

        $totalData        = Tour::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id)
            ->count();

        $totalFiltered    = $totalData;
        $limit            = (int)$request->input('length');
        $start            = (int)$request->input('start');

        $q                = Tour::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id);

        /* Search starts here*/
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $q->where(function ($query) use ($search) {
                $query->where('tour_name', 'like', "%{$search}%")
                    ->orWhere('tour_no', 'like', "%{$search}%");
            });

            $totalFiltered = $q->where(function ($query) use ($search) {
                $query->where('tour_name', 'like', "%{$search}%")
                    ->orWhere('tour_no', 'like', "%{$search}%");
            })
                ->count();
        }


        /* thead search functionality for Tour number, Tour Name begin */
        if (!empty($params['columns'][0]['search']['value'])) {
            $q->where(function ($query) use ($params) {
                $query->where('tour_no', 'like', "%{$params['columns'][0]['search']['value']}%");
            });

            $totalFiltered = $q->where(function ($query) use ($params) {
                $query->where('tour_no', 'like', "%{$params['columns'][0]['search']['value']}%");
            })
                ->count();
        }

        if (!empty($params['columns'][1]['search']['value'])) {
            $q->where(function ($query) use ($params) {
                $query->where('tour_name', 'like', "%{$params['columns'][1]['search']['value']}%");
            });

            $totalFiltered = $q->where(function ($query) use ($params) {
                $query->where('tour_name', 'like', "%{$params['columns'][1]['search']['value']}%");
            })
                ->count();
        }
        /* thead search functionality for  Tour number, Tour Name  end */

        $tours        = $q->skip($start)
            ->take($limit)
            ->orderBy('_id', 'desc')
            ->get();

        $data         = array();
        $noData       = '<span class="label label-default">' . __("mountainschool.noResult") . '</span>';

        if(!empty($tours)) {
            foreach($tours as $key => $tour) {
                /* Fetch cabin and check cabins are exist in database */
                $nestedData['tour_no']   = ($tour->tour_no) ? $tour->tour_no : $noData;
                $nestedData['tour_name'] = ($tour->tour_name) ? $tour->tour_name : $noData;
                $nestedData['no_cabins'] = ($tour->no_cabins) ? $tour->no_cabins : $noData;
                $nestedData['cabins']    = $tour->cabins;
                $nestedData['date']      = ($tour->createdate)->format('d.m.y');
                $nestedData['Edit']      = '<a href="/mountainschool/tours/edittour/' . $tour->_id . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                $data[]                  = $nestedData;
            }

            $json_data = array(
                'draw' => (int)$params['draw'],
                'recordsTotal' => (int)$totalData,
                'recordsFiltered' => (int)$totalFiltered,
                'data' => $data
            );

            return response()->json($json_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTourNewBooking()
    {
        $tourList = $this->toursList();

        return view('mountainschool.newBooking', ['tourList' => $tourList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTour()
    {
        $cabins = $this->getCabins();

        return view('mountainschool.createTour', ['cabins' => $cabins]);
    }

    /**
     * Display the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function getCabins()
    {
        $cabins = Cabin::where('is_delete', 0)
            ->orderBy('name')
            ->get();

        if($cabins)
        {
            return $cabins;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Http\Requests\TourRequest
     * @return \Illuminate\Http\Response
     */
    public function createNewCabin(TourRequest $request)
    {
        if (isset($request->formPart) && $request->formPart === 'createCabin') {
            $cabin                  = new Cabin;
            $cabin->website         = $request->website;
            $cabin->name            = $request->cabin_name;
            $cabin->contact_person  = $request->contact_person;
            $cabin->other_cabin     = "1";
            $cabin->is_delete       = 0;
            $cabin->created_at      = date('Y-m-d H:i:s');
            $cabin->save();

            return response()->json(['successMsg' => __('tours.successMsgSave')]);
        }
        else {
            return response()->json(['errorMsg' => __('tours.failure')]);
        }
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
     * @param  \App\Http\Requests\TourRequest
     * @return \Illuminate\Http\Response
     */
    public function store(TourRequest $request)
    {
        if (isset($request->formPart) && $request->formPart === 'createTour') {
            $tour             = new Tour;
            $tour->tour_name  = $request->tour_name;
            $tour->tour_no    = $request->tour_no;
            $tour->cabins     = $request->cabins;
            $tour->no_cabins  = $request->no_cabins;
            $tour->status     = 1;
            $tour->is_delete  = 0;
            $tour->user_id    = Auth::user()->_id;
            $tour->save();

            $request->session()->flash('successMsgSave', __('tours.successMsgSave'));
            $request->session()->flash('message-type', 'success');

            return response()->json(['successMsg' => __('tours.successMsgSave')]);
        }
        else {
            return response()->json(['errorMsg' => __('tours.failure')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function addNewCabin()
    {
        return view('mountainschool.addNewCabin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editTour($id)
    {
        $cabins    = $this->getCabins();

        $tour      = Tour::where('_id', new \MongoDB\BSON\ObjectID($id))->first();

        return view('mountainschool.editTour', ['cabins' => $cabins, 'tour' => $tour]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TourRequest
     * @return \Illuminate\Http\Response
     */
    public function updateTour(TourRequest $request)
    {
        if (isset($request->formPart) && $request->formPart === 'updateTour') {

            $tour            = Tour::findOrFail($request->udtId);
            $tour->tour_name = $request->tour_name;
            $tour->tour_no   = $request->tour_no;
            $tour->cabins    = $request->cabins;
            $tour->no_cabins = $request->no_cabins;
            $tour->save();

            return response()->json(['successMsg' => __('tours.successMsgUdt')]);
        }
        else {
            return response()->json(['errorMsg' => __('tours.failure')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        $userDetails = Userlist::where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();
        return view('mountainschool.updatePassword', array('user' => $userDetails));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditMyData()
    {
        /*$userDetails = Userlist::where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();

        //  $userDetails->usrBirthday =  $userDetails->usrBirthday->format('d.m.Y');;

        $utcdatetime = $userDetails->usrBirthday;
        $datetime = $utcdatetime->toDateTime();
        $dateInUTC = $datetime->format(DATE_RSS);
        $time = strtotime($dateInUTC . ' UTC');
        $dateInLocal = date("d.m.Y", $time);
        $userDetails->usrBirthday = $dateInLocal;


        return view('mountainschool.myDataEdit', array('userDetails' => $userDetails));*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TourRequest
     * @return \Illuminate\Http\Response
     */
    public function updateMyData(TourRequest $request)
    {
        /*if (isset($request->updateContact) && $request->updateContact == 'updateContact') {

            if ($request->hasFile('usrLogo')) {
                $image = $request->file('usrLogo');
                $img_name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/mschool/');
                $image->move($destinationPath, $img_name);


            }
            // Mongo UTCDateTime begin
            $date_now = $request->birthDay;
            $orig_date = new DateTime($date_now);
            $orig_date = $orig_date->getTimestamp();
            $birthDay = new \MongoDB\BSON\UTCDateTime($orig_date * 1000);
            // Mongo UTCDateTime end

            $user = Userlist::findOrFail($request->udtId);

            $user->usrFirstname = $request->firstname;
            $user->usrLastname = $request->lastname;
            $user->usrMobile = $request->mobile;
            $user->usrTelephone = $request->telephone;
            $user->usrZip = $request->zip;
            $user->usrCity = $request->city;
            $user->usrAddress = $request->street;
            $user->usrCountry = $request->country;
            $user->usrEmail = $request->email;
            $user->usrBirthday = $birthDay;
            //$user->usrName = $request->userName;
            $user->usrMschoolName = $request->usrMschoolName;
            if ($request->hasFile('usrLogo')) {
                $user->usrLogo = $img_name;
            }

            $user->save();
            return back()->with('success', __('tours.successMsgConUpt'));
        } else {
            return back()->with('failure', __('tours.failure'));

        }*/
    }

    /**
     * get all tours for MSchool loggined  user
     *
     * @param
     * @return \Illuminate\Http\Response
     */

    public function toursList()
    {
        $tours = Tour::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id)
            ->orderBy('createdate', 'desc')
            ->get();

        return $tours;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Get Tour for booking
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function getTourForBooking($id)
    {
        /* Get basic settings data */
        $basic_settings = Settings::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id)
            ->first();

        /* Get tour */
        $tours          = Tour::where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID($id))
            ->where('user_id', Auth::user()->_id)
            ->first();

        if (!empty($tours->cabins)) {
            $cabin_array = [];

            foreach ($tours->cabins as $key => $val) {
                $cabinDetails = Cabin::select('_id', 'name', 'sleeping_place', 'beds', 'dormitory', 'other_cabin', 'halfboard', 'halfboard_price')
                    ->where('is_delete', 0)
                    ->where('name', $val)
                    ->first();

                $cabin_array[$key]['name']            = $cabinDetails->name;
                $cabin_array[$key]['cId']             = $cabinDetails->_id;
                $cabin_array[$key]['sleeping_place']  = $cabinDetails->sleeping_place;
                $cabin_array[$key]['beds']            = $cabinDetails->beds;
                $cabin_array[$key]['dormitory']       = $cabinDetails->dormitory;
                $cabin_array[$key]['other_cabin']     = $cabinDetails->other_cabin;
                $cabin_array[$key]['halfboard']       = $cabinDetails->halfboard;
                $cabin_array[$key]['halfboard_price'] = $cabinDetails->halfboard_price;
                $tours->cabins                        = $cabin_array;
            }
        }

        if ($basic_settings) {
            $tours->basic_settings = $basic_settings;
        }

        return view('mountainschool.getTourCabin', ['tour' => $tours]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TourRequest
     * @return \Illuminate\Http\Response
     */
    public function bookingStore(TourRequest $request)
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

                                    // Checking bookings available begins
                                    $mon_day              = ($cabinDetails->mon_day === 1) ? 'Mon' : 0;
                                    $tue_day              = ($cabinDetails->tue_day === 1) ? 'Tue' : 0;
                                    $wed_day              = ($cabinDetails->wed_day === 1) ? 'Wed' : 0;
                                    $thu_day              = ($cabinDetails->thu_day === 1) ? 'Thu' : 0;
                                    $fri_day              = ($cabinDetails->fri_day === 1) ? 'Fri' : 0;
                                    $sat_day              = ($cabinDetails->sat_day === 1) ? 'Sat' : 0;
                                    $sun_day              = ($cabinDetails->sun_day === 1) ? 'Sun' : 0;

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
                                                            return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                        }

                                                        if($dormsRequest <= $not_regular_dorms_avail) {
                                                            $availableStatus[] = 'available';
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
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
                                                        return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                    }
                                                }
                                            }

                                            /* Calculating beds & dorms for regular */
                                            if($cabinDetails->regular === 1) {
                                                if($mon_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->mon_beds) || ($totalDorms < $cabinDetails->mon_dorms)) {
                                                            $mon_beds_diff              = $cabinDetails->mon_beds - $totalBeds;
                                                            $mon_dorms_diff             = $cabinDetails->mon_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular monday */
                                                            $mon_beds_avail             = ($mon_beds_diff >= 0) ? $mon_beds_diff : 0;
                                                            $mon_dorms_avail            = ($mon_dorms_diff >= 0) ? $mon_dorms_diff : 0;

                                                            if($bedsRequest <= $mon_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $mon_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->mon_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->mon_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->mon_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->mon_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($tue_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->tue_beds) || ($totalDorms < $cabinDetails->tue_dorms)) {
                                                            $tue_beds_diff              = $cabinDetails->tue_beds - $totalBeds;
                                                            $tue_dorms_diff             = $cabinDetails->tue_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular tuesday */
                                                            $tue_beds_avail             = ($tue_beds_diff >= 0) ? $tue_beds_diff : 0;
                                                            $tue_dorms_avail            = ($tue_dorms_diff >= 0) ? $tue_dorms_diff : 0;

                                                            if($bedsRequest <= $tue_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $tue_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->tue_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->tue_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->tue_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->tue_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($wed_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->wed_beds) || ($totalDorms < $cabinDetails->wed_dorms)) {
                                                            $wed_beds_diff              = $cabinDetails->wed_beds - $totalBeds;
                                                            $wed_dorms_diff             = $cabinDetails->wed_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular wednesday */
                                                            $wed_beds_avail             = ($wed_beds_diff >= 0) ? $wed_beds_diff : 0;
                                                            $wed_dorms_avail            = ($wed_dorms_diff >= 0) ? $wed_dorms_diff : 0;

                                                            if($bedsRequest <= $wed_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $wed_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->wed_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->wed_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->wed_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->wed_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($thu_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->thu_beds) || ($totalDorms < $cabinDetails->thu_dorms)) {
                                                            $thu_beds_diff              = $cabinDetails->thu_beds - $totalBeds;
                                                            $thu_dorms_diff             = $cabinDetails->thu_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular thursday */
                                                            $thu_beds_avail             = ($thu_beds_diff >= 0) ? $thu_beds_diff : 0;
                                                            $thu_dorms_avail            = ($thu_dorms_diff >= 0) ? $thu_dorms_diff : 0;

                                                            if($bedsRequest <= $thu_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $thu_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->thu_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->thu_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->thu_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->thu_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($fri_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->fri_beds) || ($totalDorms < $cabinDetails->fri_dorms)) {
                                                            $fri_beds_diff         = $cabinDetails->fri_beds - $totalBeds;
                                                            $fri_dorms_diff        = $cabinDetails->fri_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular friday */
                                                            $fri_beds_avail        = ($fri_beds_diff >= 0) ? $fri_beds_diff : 0;
                                                            $fri_dorms_avail       = ($fri_dorms_diff >= 0) ? $fri_dorms_diff : 0;

                                                            if($bedsRequest <= $fri_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $fri_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->fri_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->fri_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' =>  __("tours.inquiryAlert").$generateBookingDate->format("d.m"). __("tours.inquiryAlert1").$cabinDetails->fri_inquiry_guest. __("tours.inquiryAlert2").$clickHere. __("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->fri_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($sat_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->sat_beds) || ($totalDorms < $cabinDetails->sat_dorms)) {
                                                            $sat_beds_diff         = $cabinDetails->sat_beds - $totalBeds;
                                                            $sat_dorms_diff        = $cabinDetails->sat_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular saturday */
                                                            $sat_beds_avail        = ($sat_beds_diff >= 0) ? $sat_beds_diff : 0;
                                                            $sat_dorms_avail       = ($sat_dorms_diff >= 0) ? $sat_dorms_diff : 0;

                                                            if($bedsRequest <= $sat_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $sat_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->sat_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->sat_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' =>  __("tours.inquiryAlert").$generateBookingDate->format("d.m"). __("tours.inquiryAlert1").$cabinDetails->sat_inquiry_guest. __("tours.inquiryAlert2").$clickHere. __("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->sat_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($sun_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalBeds < $cabinDetails->sun_beds) || ($totalDorms < $cabinDetails->sun_dorms)) {
                                                            $sun_beds_diff         = $cabinDetails->sun_beds - $totalBeds;
                                                            $sun_dorms_diff        = $cabinDetails->sun_dorms - $totalDorms;

                                                            /* Available beds and dorms on regular sunday */
                                                            $sun_beds_avail        = ($sun_beds_diff >= 0) ? $sun_beds_diff : 0;
                                                            $sun_dorms_avail       = ($sun_dorms_diff >= 0) ? $sun_dorms_diff : 0;

                                                            if($bedsRequest <= $sun_beds_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $bedsRequest.__("tours.bedsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            if($dormsRequest <= $sun_dorms_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $dormsRequest.__("tours.dormsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested beds and dorms sum is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->sun_inquiry_guest > 0 && $requestBedsSumDorms >= $cabinDetails->sun_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' =>  __("tours.inquiryAlert").$generateBookingDate->format("d.m"). __("tours.inquiryAlert1").$cabinDetails->sun_inquiry_guest. __("tours.inquiryAlert2").$clickHere. __("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->sun_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }

                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' =>  __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);

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
                                                    return response()->json(['error' =>  __("tours.alreadyFilledBedsDorms").$generateBookingDate->format("d.m")], 422);
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
                                                        return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                    }
                                                }
                                            }

                                            /* Calculating sleeps for regular */
                                            if($cabinDetails->regular === 1) {

                                                if($mon_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->mon_sleeps)) {
                                                            $mon_sleeps_diff       = $cabinDetails->mon_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular monday */
                                                            $mon_sleeps_avail      = ($mon_sleeps_diff >= 0) ? $mon_sleeps_diff : 0;

                                                            if($sleepsRequest <= $mon_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->mon_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->mon_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->mon_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->mon_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($tue_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->tue_sleeps)) {
                                                            $tue_sleeps_diff       = $cabinDetails->tue_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular tuesday */
                                                            $tue_sleeps_avail      = ($tue_sleeps_diff >= 0) ? $tue_sleeps_diff : 0;

                                                            if($sleepsRequest <= $tue_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->tue_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->tue_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->tue_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->tue_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($wed_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->wed_sleeps)) {
                                                            $wed_sleeps_diff       = $cabinDetails->wed_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular wednesday */
                                                            $wed_sleeps_avail      = ($wed_sleeps_diff >= 0) ? $wed_sleeps_diff : 0;

                                                            if($sleepsRequest <= $wed_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->wed_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->wed_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("v.inquiryAlert1").$cabinDetails->wed_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->wed_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($thu_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->thu_sleeps)) {
                                                            $thu_sleeps_diff       = $cabinDetails->thu_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular thursday */
                                                            $thu_sleeps_avail      = ($thu_sleeps_diff >= 0) ? $thu_sleeps_diff : 0;

                                                            if($sleepsRequest <= $thu_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->thu_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->thu_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->thu_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->thu_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($fri_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->fri_sleeps)) {
                                                            $fri_sleeps_diff       = $cabinDetails->fri_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular friday */
                                                            $fri_sleeps_avail      = ($fri_sleeps_diff >= 0) ? $fri_sleeps_diff : 0;

                                                            if($sleepsRequest <= $fri_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->fri_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->fri_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->fri_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->fri_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($sat_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->sat_sleeps)) {
                                                            $sat_sleeps_diff       = $cabinDetails->sat_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular saturday */
                                                            $sat_sleeps_avail      = ($sat_sleeps_diff >= 0) ? $sat_sleeps_diff : 0;

                                                            if($sleepsRequest <= $sat_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->sat_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->sat_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->sat_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->sat_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                        }
                                                    }
                                                }

                                                if($sun_day === $day) {

                                                    if(!in_array($dates, $dates_array)) {

                                                        $dates_array[] = $dates;

                                                        if(($totalSleeps < $cabinDetails->sun_sleeps)) {
                                                            $sun_sleeps_diff       = $cabinDetails->sun_sleeps - $totalSleeps;

                                                            /* Available sleeps on regular sunday */
                                                            $sun_sleeps_avail      = ($sun_sleeps_diff >= 0) ? $sun_sleeps_diff : 0;

                                                            if($sleepsRequest <= $sun_sleeps_avail) {
                                                                $availableStatus[] = 'available';
                                                            }
                                                            else {
                                                                $availableStatus[] = 'notAvailable';
                                                                return response()->json(['error' => $sleepsRequest.__("tours.sleepsNotAvailable").$generateBookingDate->format("d.m")], 422);
                                                            }

                                                            /* Checking requested sleeps is greater or equal to inquiry. Cabin inquiry guest is greater than 0 */
                                                            if($cabinDetails->sun_inquiry_guest > 0 && $sleepsRequest >= $cabinDetails->sun_inquiry_guest) {
                                                                $availableStatus[] = 'notAvailable';
                                                                /*return response()->json(['error' => __("tours.inquiryAlert").$generateBookingDate->format("d.m").__("tours.inquiryAlert1").$cabinDetails->sun_inquiry_guest.__("tours.inquiryAlert2").$clickHere.__("tours.inquiryAlert3")], 422);*/
                                                                return response()->json(['error', __("tours.bookingLimitReached").$generateBookingDate->format("d.m").__("tours.bookingLimitReachedOne").($cabinDetails->sun_inquiry_guest - 1).__("tours.bookingLimitReachedTwo")], 422);
                                                            }
                                                        }
                                                        else {
                                                            $availableStatus[] = 'notAvailable';
                                                            return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
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
                                                    return response()->json(['error' => __("tours.alreadyFilledSleeps").$generateBookingDate->format("d.m")], 422);
                                                }

                                            }
                                        }
                                        else {
                                            return response()->json(['error' => __("tours.sleepsNotMatchGuestGuide"), 'bookingOrder' => $i], 422);
                                        }
                                    }
                                }

                                $tour = Tour::where('_id', new \MongoDB\BSON\ObjectID($request->tour_name))->first();

                                // Save Booking Data
                                $booking = new MountSchoolBooking;

                                $booking->tour_name      = $tour['name'];
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
     * duplicatingBooking for getting saved booking
     *
     * @return \Illuminate\Http\Response
     */
    protected function duplicatingBooking(Request $request)
    {
        /*$tour = Tour::where('_id', $request->tourId)->first();

        // Getting bookings from mschool collection
        $msBookings = MountSchoolBooking::where('is_delete', 0)
            ->where('tour_name', $tour->tour_name)
            ->first();

        dd($msBookings);*/
    }
    /**
     * basicSettings
     *
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function basicSettings()
    {
        $basic_settings = Settings::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id)
            ->first();

        return view('mountainschool.basicSettings', ['basicSettings' => $basic_settings]);
    }

    /**
     * Update basic settings
     *
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function updateBasicSettings(TourRequest $request)
    {
        if($request->has('updateBasicSettings')) {
            Settings::updateOrCreate(
                ['user_id' => Auth::user()->_id, 'is_delete' => 0],
                ['contact_person' => $request->contact_person, 'no_guides' => (int)$request->no_guides, 'half_board' => $request->half_board, 'beds' => (int)$request->beds, 'dorms' => (int)$request->dorms, 'sleeps' => (int)$request->sleeps, 'guests' => (int)$request->guests]
            );

            return redirect()->back()->with('success', __('tours.successMsgbsUpt'));
        }
        else {
            abort(404);
        }
    }
}
