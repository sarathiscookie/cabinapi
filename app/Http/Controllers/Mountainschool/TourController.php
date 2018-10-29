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

class TourController extends Controller
{
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
     * updatePassword the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(TourRequest $request)
    {

        /*   $obj_user = Userlist::where('is_delete', 0)
               ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
               ->first();
           $curPassword = $request->current_pwd;
           $newPassword = $request->new_pwd;

           if (Hash::check($curPassword, $obj_user->usrPassword)) {

               $obj_user->usrPassword = bcrypt($newPassword);
               $obj_user->save();
               return back()->with('success', __('tours.successMsgPwdUpt'));

           } else {
               $error['current_pwd'] = 'The entered password does not match the database password';
               return back()->withErrors($error)->withInput();

           }
   */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditMyData()
    {
        $userDetails = Userlist::where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();

        //  $userDetails->usrBirthday =  $userDetails->usrBirthday->format('d.m.Y');;

        $utcdatetime = $userDetails->usrBirthday;
        $datetime = $utcdatetime->toDateTime();
        $dateInUTC = $datetime->format(DATE_RSS);
        $time = strtotime($dateInUTC . ' UTC');
        $dateInLocal = date("d.m.Y", $time);
        $userDetails->usrBirthday = $dateInLocal;


        return view('mountainschool.myDataEdit', array('userDetails' => $userDetails));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param
     * @return \Illuminate\Http\Response
     */
    public function updateMyData(TourRequest $request)
    {
        if (isset($request->updateContact) && $request->updateContact == 'updateContact') {

            if ($request->hasFile('usrLogo')) {
                $image = $request->file('usrLogo');
                $img_name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/mschool/');
                $image->move($destinationPath, $img_name);


            }
            /* Mongo UTCDateTime begin */
            $date_now = $request->birthDay;
            $orig_date = new DateTime($date_now);
            $orig_date = $orig_date->getTimestamp();
            $birthDay = new \MongoDB\BSON\UTCDateTime($orig_date * 1000);
            /* Mongo UTCDateTime end */

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
          //  $user->usrName = $request->userName;
            $user->usrMschoolName = $request->usrMschoolName;
            if ($request->hasFile('usrLogo')) {
                $user->usrLogo = $img_name;
            }

            $user->save();
            return back()->with('success', __('tours.successMsgConUpt'));
        } else {
            return back()->with('failure', __('tours.failure'));

        }
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

        if($tours) {
            return $tours;
        }
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
                $cabinDetails = Cabin::where('is_delete', 0)
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookingStore(TourRequest $request)
    {


        if (isset($request->formPart) && $request->formPart == 'newBooking' && $request->hidProceed == 'valSuccess' ) {

            /* Mongo UTCDateTime begin */
            $date_now = date("Y-m-d H:i:s");
            $orig_date = new DateTime($date_now);
            $orig_date = $orig_date->getTimestamp();
            $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date * 1000);

            /* Mongo UTCDateTime end */

            for ($tb = 0; $tb <count($request->get('ind_tour_no')); $tb++) {

                for ($i = 1; $i <= $request->no_cabins; $i++) {
                    $tour = new MountSchoolBooking;
                    $cabinId = 'cabinId' . $i;
                    $no_guides = 'no_guides' . $i;
                    $guests = 'guests' . $i;
                    $check_in = 'check_in' . $i;
                    $days = 'days' . $i;
                    $halfboard = 'hidHalfboard' . $i;
                    $dormitory = 'dormitory' . $i;
                    $beds = 'beds' . $i;

                    /* Cabin Details*/
                    $cabinDetails = Cabin::where('is_delete', 0)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->$cabinId[$tb]))
                        ->first();

                    /* Create invoice number begin */
                    if ($cabinDetails->invoice_autonum && $cabinDetails->invoice_autonum != '') {
                        $autoNumber = (int)$cabinDetails->invoice_autonum + 1;
                    } else {
                        $autoNumber = 100000;
                    }

                    if ($cabinDetails->invoice_code && $cabinDetails->invoice_code != '') {
                        $invoiceCode = $cabinDetails->invoice_code;
                        $invoiceNumber = $invoiceCode . "-" . date("y") . "-" . $autoNumber;
                    }else{
                        $invoiceNumber =   date("y") . "-" . $autoNumber;
                    }
                    /* Create invoice number end */
                    /* Getting tour  from mschool collection  */
                    $tourName = Tour::where('_id', $request->tour_name)->first();


                    /* */

                    $tour->tour_name = $tourName->tour_name;
                    $tour->ind_tour_no = $request->ind_tour_no[$tb];
                    $tour->tour_guide = $request->tour_guide[$tb];
                    $tour->ind_notice = $request->ind_notice[$tb];
                    $tour->no_guides = $request->$no_guides[$tb];
                    $tour->total_guests = $request->$guests[$tb];
                    $tour->guests = $request->$guests[$tb];


                    $tour->cabin_name = $cabinDetails->name;
                    $tour->check_in = $this->getDateUtc($request->$check_in[$tb]);
                    $days = $request->$days[$tb];

                    $days = (int)$days;
                    $addDays = "+$days day";

                    $reserve_to = DateTime::createFromFormat('d.m.y', $request->$check_in[$tb])->modify($addDays)->format('d.m.y');

                    $tour->reserve_to = $this->getDateUtc($reserve_to);
                    $tour->is_delete = 0;
                    $tour->bookingdate = $utcdatetime;
                  //  $halfboardVal = '';
                   // if (isset($request->$halfboard[$tb] ) && ($request->$halfboard[$tb] == "on" || $request->$halfboard[$tb] == "1")) {
                        $halfboardVal = "1";
                   // }
                    $tour->half_board = $request->$halfboard[$tb];

                    $tour->other_cabin = $cabinDetails->other_cabin;
                    $tour->invoice_number = $invoiceNumber;
                    $sleeps = $request->$no_guides[$tb] + $request->$guests[$tb];
                    $tour->sleeps = $sleeps;
                    $tour->status = 1;
                    $tour->beds = $request->$beds[$tb];
                    $tour->dorms = $request->$dormitory[$tb];
                    $tour->user_id = new \MongoDB\BSON\ObjectID(Auth::user()->_id);

                    $tour->save();

                    /* Update cabin invoice_autonum begin */
                    Cabin::where('is_delete', 0)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->$cabinId[$tb]))
                        ->update(['invoice_autonum' => $autoNumber]);
                    /* Update cabin invoice_autonum end */


                }
            }
            //  $tour->user_id = Mschool::user()->_id;
            //  $tour->save();
            //  $request->session()->flash('successMsgSave', __('tours.successMsgSave'));
            //  $request->session()->flash('message-type', 'success');
            //  echo json_encode(array('successMsg' => __('tours.successMsgBooking')));
          return response()->json(['successMsg' =>  __('tours.successMsgBooking')], 200);

        } else {
            return response()->json(['failureMsg' =>  __('tours.failureMsgBooking')]);
            //echo json_encode(array('errorMsg' => __('tours.failure')));
        }
    }


    /**
     * duplicatingBooking for getting saved booking
     *
     * @param tour id
     * @return \Illuminate\Http\Response
     */
    protected function duplicatingBooking(Request $request)
    {
        $tour = Tour::where('_id', $request->tourId)->first();

        /* Getting bookings from mschool collection  */
        $msBookings = MountSchoolBooking::where('is_delete', 0)
            ->where('tour_name', $tour->tour_name)
            ->first();

        dd($msBookings);
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


    /**
     * To generate date format as mongo.
     *
     * @param  string $date
     * @return \Illuminate\Http\Response
     */
    protected function getDateUtc($date)
    {
        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime = new DateTime($dateFormatChange);
        $timeStamp = $dateTime->getTimestamp();
        $utcDateTime = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);
        return $utcDateTime;
    }

    /**
     * Check available data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $available = 'failure';


        /*     if(session('sleeping_place') != 1)
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
 */
        if ($request->dateFrom != null) {

            $holiday_prepare = [];
            $regular_dates_array = [];
            $not_regular_dates = [];

            $dorms = 0;
            $beds = 0;
            $sleeps = 0;

            $msSleeps = 0;
            $msBeds = 0;
            $msDorms = 0;

            $availableStatus = [];


            $days = (int)$request->selDays;
            $addDays = "+$days day";


            $dateBegin = DateTime::createFromFormat('d.m.y', $request->dateFrom)->format('Y-m-d');
            $dateEnd = DateTime::createFromFormat('d.m.y', $request->dateFrom)->modify($addDays)->format('Y-m-d');


            $d1 = new DateTime($dateBegin);
            $d2 = new DateTime($dateEnd);
            $dateDifference = $d2->diff($d1);

            $generateBookingDates = $this->generateDates($dateBegin, $dateEnd);

            /* Cabin Details*/
            $cabinDetails = Cabin::where('is_delete', 0)
                ->where('_id', new \MongoDB\BSON\ObjectID($request->cabinId))
                ->first();


            $seasons = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabinId))
                ->get();

            foreach ($generateBookingDates as $key => $generateBookingDate) {

                if ($dateDifference->days <= 60) {

                    $generateBookingDat = $generateBookingDate->format('Y-m-d'); //2017-09-02,2017-09-03,2017-09-04,2017-09-05,2017-09-06,2017-09-07,2017-09-08,2017-09-09,2017-09-10,2017-09-11
                    $generateBookingDay = $generateBookingDate->format('D'); //Sat,Sun,Mon,Tue,Wed,Thu,Fri,Sat,Sun,Mon
                    $bookingDateSeasonType = null;

                    /* Checking season begin */
                    if ($seasons) {
                        foreach ($seasons as $season) {
                            if (($season->summerSeasonStatus === 'open') && ($season->summerSeason === 1) && ($generateBookingDat >= ($season->earliest_summer_open)->format('Y-m-d')) && ($generateBookingDat < ($season->latest_summer_close)->format('Y-m-d'))) {
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
                            } elseif (($season->winterSeasonStatus === 'open') && ($season->winterSeason === 1) && ($generateBookingDat >= ($season->earliest_winter_open)->format('Y-m-d')) && ($generateBookingDat < ($season->latest_winter_close)->format('Y-m-d'))) {
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

                        if (!$bookingDateSeasonType) {
                            //print_r($generateBookingDat . ' Sorry not a season time ');
                            return response()->json(['error' => 'Sorry selected dates are not in a season time.'], 422);
                        }
                        /*else
                        {
                            print_r($generateBookingDat . ' booked on ' . $bookingDateSeasonType . ' season ');
                        }*/

                        $prepareArray = [$generateBookingDat => $generateBookingDay];
                        $array_unique = array_unique($holiday_prepare);
                        $array_intersect = array_intersect($prepareArray, $array_unique);

                        foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {

                            if ($dateBegin === $array_intersect_key) {
                                return response()->json(['error' => $array_intersect_values . ' is a holiday.'], 422);
                            }

                            if ($array_intersect_key > $dateBegin && $array_intersect_key < $dateEnd) {
                                return response()->json(['error' => 'Booking not possible because holidays included.'], 422);
                            }

                            //--------------

                            $disableDates[] = $array_intersect_key;


                        }
                    }
                    /* Checking season end */

                    /* Checking bookings available begin */
                    $session_mon_day = ($cabinDetails->mon_day === 1) ? 'Mon' : 0;
                    $session_tue_day = ($cabinDetails->tue_day === 1) ? 'Tue' : 0;
                    $session_wed_day = ($cabinDetails->wed_day === 1) ? 'Wed' : 0;
                    $session_thu_day = ($cabinDetails->thu_day === 1) ? 'Thu' : 0;
                    $session_fri_day = ($cabinDetails->fri_day === 1) ? 'Fri' : 0;
                    $session_sat_day = ($cabinDetails->sat_day === 1) ? 'Sat' : 0;
                    $session_sun_day = ($cabinDetails->sun_day === 1) ? 'Sun' : 0;

                    /* Getting bookings from booking collection status is 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart */
                    $bookings = Booking::select('beds', 'dormitory', 'sleeps')
                        ->where('is_delete', 0)
                        ->where('cabinname', $cabinDetails->name)
                        ->whereIn('status', ['1', '4', '7', '8'])
                        ->whereRaw(['checkin_from' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                        ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                        ->get();

                    /* Getting bookings from mschool collection status is 1=> Fix, 2=> Cancel, 3=> Completed, 4=> Request (Reservation), 5=> Waiting for payment, 6=> Expired, 7=> Inquiry, 8=> Cart */
                    $msBookings = MountSchoolBooking::select('beds', 'dormitory', 'sleeps')
                        ->where('is_delete', 0)
                        ->where('cabin_name', $cabinDetails->name)
                        ->whereIn('status', ['1', '4', '7', '8'])
                        ->whereRaw(['check_in' => array('$lte' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                        ->whereRaw(['reserve_to' => array('$gt' => $this->getDateUtc($generateBookingDate->format('d.m.y')))])
                        ->get();

                    /* Getting count of sleeps, beds and dorms */
                    if (count($bookings) > 0 || count($msBookings) > 0) {
                        $sleeps = $bookings->sum('sleeps');
                        $beds = $bookings->sum('beds');
                        $dorms = $bookings->sum('dormitory');
                        $msSleeps = $msBookings->sum('sleeps');
                        $msBeds = $msBookings->sum('beds');
                        $msDorms = $msBookings->sum('dormitory');
                    }

                    /* Taking beds, dorms and sleeps depends up on sleeping_place */
                    if ($cabinDetails->sleeping_place != 1) {

                        $totalBeds = $beds + $msBeds;
                        $totalDorms = $dorms + $msDorms;

                        /* Calculating beds & dorms of regular and not regular booking */
                        if ($cabinDetails->regular || $cabinDetails->not_regular) {

                            if ($cabinDetails->not_regular === 1) {

                                $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                $not_regular_date_begin = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                $not_regular_date_end = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                $generateNotRegularDates = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                foreach ($generateNotRegularDates as $generateNotRegularDate) {
                                    $not_regular_dates[] = $generateNotRegularDate->format('Y-m-d');
                                }

                                //print_r($not_regular_dates); //[2017-09-01 2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02], [2017-09-01  2017-09-02, 2017-09-01  2017-09-02, 2017-09-01  2017-09-02]
                                //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]
                                if (in_array($generateBookingDat, $not_regular_dates)) {

                                    $regular_dates_array[] = $generateBookingDat;

                                    if (($totalBeds < $cabinDetails->not_regular_beds) || ($totalDorms < $cabinDetails->not_regular_dorms)) {

                                        $available_not_regular_beds = $cabinDetails->not_regular_beds - $totalBeds;

                                        if ($request->beds <= $available_not_regular_beds) {
                                            //print_r(' Not regular beds available '.' availableBeds ' . $available_not_regular_beds);
                                            $availableStatus[] = 'available';
                                        } else {
                                            //print_r(' Not regular beds not available '.' availableBeds ' . $available_not_regular_beds);
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                        }

                                        $available_not_regular_dorms = $cabinDetails->not_regular_dorms - $totalDorms;

                                        if ($request->dorms <= $available_not_regular_dorms) {
                                            //print_r(' Not regular dorms available '.' availableDorms ' . $available_not_regular_dorms);
                                            $availableStatus[] = 'available';
                                        } else {
                                            //print_r(' Not regular dorms not available '.' availableDorms ' . $available_not_regular_dorms);
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                        }
                                    } else {
                                        $availableStatus[] = 'notAvailable';
                                        return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                    }

                                    //print_r(' Date '.$generateBookingDat.' not_regular_beds: '.session('not_regular_beds').' totalBeds '. $totalBeds . ' not_regular_dorms: '.session('not_regular_dorms').' totalDorms '. $totalDorms);
                                }

                                //print_r($regular_dates_array); //2017-09-02, 2017-09-03


                                /*session('not_regular_beds');
                                session('not_regular_dorms');
                                session('not_regular_sleeps');*/
                            }

                            if ($cabinDetails->regular === 1) {

                                if ($session_mon_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->mon_beds) || ($totalDorms < $cabinDetails->mon_dorms)) {

                                            $available_mon_beds = $cabinDetails->mon_beds - $totalBeds;

                                            if ($request->beds <= $available_mon_beds) {
                                                //print_r(' mon beds available '.' available_mon_beds ' . $available_mon_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r(' mon beds not available '.' available_mon_beds ' . $available_mon_beds);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_mon_dorms = $cabinDetails->mon_dorms - $totalDorms;

                                            if ($request->dorms <= $available_mon_dorms) {
                                                //print_r(' mon dorms available '.' available_mon_dorms ' . $available_mon_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r(' mon dorms not available '.' available_mon_dorms ' . $available_mon_dorms);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }
                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' mon_beds: '.session('mon_beds').' totalBeds '. $totalBeds . ' mon_dorms: '.session('mon_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_tue_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->tue_beds) || ($totalDorms < $cabinDetails->tue_dorms)) {

                                            $available_tue_beds = $cabinDetails->tue_beds - $totalBeds;

                                            if ($request->beds <= $available_tue_beds) {
                                                //print_r(' tue_beds available '.' available_tue_beds ' . $available_tue_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r(' tue_beds not available '.' available_tue_beds ' . $available_tue_beds);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_tue_dorms = $cabinDetails->tue_dorms - $totalDorms;

                                            if ($request->dorms <= $available_tue_dorms) {
                                                //print_r(' tue_dorms available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r(' tue_dorms not available ' .' available_tue_dorms ' . $available_tue_dorms);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' tue_beds: '.session('tue_beds').' totalBeds '. $totalBeds . ' tue_dorms: '.session('tue_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_wed_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->wed_beds) || ($totalDorms < $cabinDetails->wed_dorms)) {

                                            $available_wed_beds = $cabinDetails->wed_beds - $totalBeds;

                                            if ($request->beds <= $available_wed_beds) {
                                                //print_r(' wed_beds available '.' available_wed_beds ' . $available_wed_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_wed_dorms = $cabinDetails->wed_dorms - $totalDorms;

                                            if ($request->dorms <= $available_wed_dorms) {
                                                //print_r(' wed_dorms available '.' available_wed_dorms ' . $available_wed_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }
                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' wed_beds: '.session('wed_beds').' totalBeds '. $totalBeds . ' wed_dorms: '.session('wed_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_thu_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->thu_beds) || ($totalDorms < $cabinDetails->thu_dorms)) {

                                            $available_thu_beds = $cabinDetails->thu_beds - $totalBeds;

                                            if ($request->beds <= $available_thu_beds) {
                                                //print_r(' thu_beds available '.' available_thu_beds ' . $available_thu_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_thu_dorms = $cabinDetails->thu_dorms - $totalDorms;

                                            if ($request->dorms <= $available_thu_dorms) {
                                                //print_r(' thu_dorms available '.' available_thu_dorms ' . $available_thu_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' thu_beds: '.session('thu_beds').' totalBeds '. $totalBeds . ' thu_dorms: '.session('thu_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_fri_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->fri_beds) || ($totalDorms < $cabinDetails->fri_dorms)) {

                                            $available_fri_beds = $cabinDetails->fri_beds - $totalBeds;

                                            if ($request->beds <= $available_fri_beds) {
                                                //print_r(' fri_beds available '.' available_fri_beds ' . $available_fri_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_fri_dorms = $cabinDetails->fri_dorms - $totalDorms;

                                            if ($request->dorms <= $available_fri_dorms) {
                                                //print_r(' fri_dorms available '.' available_fri_dorms ' . $available_fri_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' fri_beds: '.session('fri_beds').' totalBeds '. $totalBeds . ' fri_dorms: '.session('fri_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_sat_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->sat_beds) || ($totalDorms < $cabinDetails->sat_dorms)) {

                                            $available_sat_beds = $cabinDetails->sat_beds - $totalBeds;

                                            if ($request->beds <= $available_sat_beds) {
                                                //print_r(' sat_beds available '.' available_sat_beds ' . $available_sat_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_sat_dorms = $cabinDetails->sat_dorms - $totalDorms;

                                            if ($request->dorms <= $available_sat_dorms) {
                                                //print_r(' sat_dorms available '.' available_sat_dorms ' . $available_sat_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' sat_beds: '.session('sat_beds').' totalBeds '. $totalBeds . ' sat_dorms: '.session('sat_dorms').' totalDorms '. $totalDorms);
                                    }

                                }

                                if ($session_sun_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if (($totalBeds < $cabinDetails->sun_beds) || ($totalDorms < $cabinDetails->sun_dorms)) {

                                            $available_sun_beds = $cabinDetails->sun_beds - $totalBeds;

                                            if ($request->beds <= $available_sun_beds) {
                                                //print_r(' sun_beds available '.' available_sun_beds ' . $available_sun_beds);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                            }

                                            $available_sun_dorms = $cabinDetails->sun_dorms - $totalDorms;

                                            if ($request->dorms <= $available_sun_dorms) {
                                                //print_r(' sun_dorms available '.' available_sun_dorms ' . $available_sun_dorms);
                                                $availableStatus[] = 'available';
                                            } else {
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' sun_beds: '.session('sun_beds').' totalBeds '. $totalBeds . ' sun_dorms: '.session('sun_dorms').' totalDorms '. $totalDorms );
                                    }

                                }
                            }
                        }

                        /* Calculating beds & dorms of normal booking */
                        //print_r(array_unique($regular_dates_array)); //[2017-09-02, 2017-09-04] //if not regular has 2017-09-04 and regular has 2017-09-04

                        //print_r($generateBookingDat); //[2017-09-02, 2017-09-03, 2017-09-04]

                        if (!in_array($generateBookingDat, $regular_dates_array)) {

                            if (($totalBeds < $cabinDetails->beds) || ($totalDorms < $cabinDetails->dormitory)) {

                                $availableBeds = $cabinDetails->beds - $totalBeds;

                                if ($request->beds <= $availableBeds) {
                                    //print_r(' Beds available '.' availableBeds ' . $availableBeds);
                                    $availableStatus[] = 'available';
                                } else {
                                    $availableStatus[] = 'notAvailable';
                                    return response()->json(['error' => 'Beds not available on ' . $generateBookingDat], 422);
                                }

                                $availableDorms = $cabinDetails->dormitory - $totalDorms;

                                if ($request->dorms <= $availableDorms) {
                                    //print_r(' Dorms available '.' availableDorms ' . $availableDorms);
                                    $availableStatus[] = 'available';
                                } else {
                                    $availableStatus[] = 'notAvailable';
                                    return response()->json(['error' => 'Dorms not available on ' . $generateBookingDat], 422);
                                }

                            } else {
                                $availableStatus[] = 'notAvailable';
                                return response()->json(['error' => 'Beds and Dorms already filled on ' . $generateBookingDat], 422);
                            }
                            //print_r(' Date '.$generateBookingDat.' beds: '.session('beds').' totalBeds '. $totalBeds . ' dormitory: '.session('dormitory').' totalDorms '. $totalDorms );
                        }

                        /*print_r(' bookBeds: '.$beds.' BookDorms: '.$dorms).'<br>';
                        print_r(' mschoolBeds: '.$msBeds.' mschoolDorms: '.$msDorms);
                        print_r(' totalBeds: '.$totalBeds.' totalDorms: '.$totalDorms);*/

                    } else {
                        $totalSleeps = $sleeps + $msSleeps;

                        /* Calculating sleeps of regular and not regular booking */
                        if ($cabinDetails->regular || $cabinDetails->not_regular) {

                            if ($cabinDetails->not_regular === 1) {

                                $not_regular_date_explode = explode(" - ", $cabinDetails->not_regular_date);
                                $not_regular_date_begin = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[0])->format('Y-m-d');
                                $not_regular_date_end = DateTime::createFromFormat('d.m.y', $not_regular_date_explode[1])->format('Y-m-d 23:59:59'); //To get the end date we need to add time
                                $generateNotRegularDates = $this->generateDates($not_regular_date_begin, $not_regular_date_end);

                                foreach ($generateNotRegularDates as $generateNotRegularDate) {
                                    $not_regular_dates[] = $generateNotRegularDate->format('Y-m-d');
                                }

                                if (in_array($generateBookingDat, $not_regular_dates)) {

                                    $regular_dates_array[] = $generateBookingDat;

                                    if ($totalSleeps < $cabinDetails->not_regular_sleeps) {

                                        $available_not_regular_sleeps = $cabinDetails->not_regular_sleeps - $totalSleeps;

                                        if ($request->sleeps <= $available_not_regular_sleeps) {
                                            //print_r(' Not regular sleeps available '.' availableSleeps' . $available_not_regular_sleeps);
                                            $availableStatus[] = 'available';
                                        } else {
                                            //print_r(' Not regular sleeps not available '.' availableSleeps' . $available_not_regular_sleeps);
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                        }

                                    } else {
                                        $availableStatus[] = 'notAvailable';
                                        return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                    }
                                    //print_r(' Date '.$generateBookingDat.' not_regular_sleeps: '.session('not_regular_sleeps').' totalSleeps '. $totalSleeps);
                                }

                            }

                            if ($cabinDetails->regular === 1) {

                                if ($session_mon_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->mon_sleeps) {

                                            $availableMonSleeps = $cabinDetails->mon_sleeps - $totalSleeps;

                                            if ($request->sleeps <= $availableMonSleeps) {
                                                //print_r('Mon sleeps available' . ' availableMonSleeps' . $availableMonSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Mon sleeps not available'. ' availableMonSleeps' . $availableMonSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' mon_sleeps: '.session('mon_sleeps').' totalSleeps '. $totalSleeps);
                                    }

                                }

                                if ($session_tue_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->tue_sleeps) {

                                            $availableTueSleeps = $cabinDetails->tue_sleeps - $totalSleeps;

                                            if ($request->sleeps <= $availableTueSleeps) {
                                                //print_r('Tue sleeps available' . ' availableTueSleeps' . $availableTueSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Tue sleeps not available' . ' availableTueSleeps' . $availableTueSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' tue_sleeps: '.session('tue_sleeps').' totalSleeps '. $totalSleeps);
                                    }

                                }

                                if ($session_wed_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->wed_sleeps) {

                                            $availableWedSleeps = $cabinDetails->wed_sleeps - $totalSleeps;

                                            if ($request->sleeps <= $availableWedSleeps) {
                                                //print_r('Wed sleeps available'. ' availableWedSleeps ' . $availableWedSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Wed sleeps not available'. ' availableWedSleeps ' . $availableWedSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' wed_sleeps: '.session('wed_sleeps').' totalSleeps '. $totalSleeps);

                                    }


                                }

                                if ($session_thu_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->thu_sleeps) {

                                            $availableThuSleeps = $cabinDetails->thu_sleeps - $totalSleeps;

                                            if ($request->sleeps <= $availableThuSleeps) {
                                                //print_r('Thu sleeps available'. ' availableThuSleeps ' . $availableThuSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Thu sleeps not available'. ' availableThuSleeps ' . $availableThuSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }
                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' thu_sleeps: '.session('thu_sleeps').' totalSleeps '. $totalSleeps);
                                    }
                                }

                                if ($session_fri_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->fri_sleeps) {

                                            $availableFriSleeps = $cabinDetails->fri_sleeps - $totalSleeps;

                                            if ($request->sleeps <= $availableFriSleeps) {
                                                //print_r('Fri sleeps available' . ' availableFriSleeps ' . $availableFriSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Fri sleeps not available' . ' availableFriSleeps ' . $availableFriSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }

                                        //print_r(' Date '.$generateBookingDat.' fri_sleeps: '.session('fri_sleeps').' totalSleeps '. $totalSleeps);

                                    }


                                }

                                if ($session_sat_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {

                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->sat_sleeps) {

                                            $availableSatSleeps = $cabinDetails->sat_sleeps - $totalSleeps;
                                            if ($request->sleeps <= $availableSatSleeps) {
                                                //print_r('Sat sleeps available' . ' availableSatSleeps ' . $availableSatSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Sat sleeps not available' . ' availableSatSleeps ' . $availableSatSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' sat_sleeps: '.session('sat_sleeps').' totalSleeps '. $totalSleeps);
                                    }


                                }

                                if ($session_sun_day === $generateBookingDay) {

                                    if (!in_array($generateBookingDat, $not_regular_dates)) {
                                        $regular_dates_array[] = $generateBookingDat;

                                        if ($totalSleeps < $cabinDetails->sun_sleeps) {

                                            $availableSunSleeps = $cabinDetails->sun_sleeps - $totalSleeps;
                                            if ($request->sleeps <= $availableSunSleeps) {
                                                //print_r('Sun sleeps available'. ' availableSunSleeps ' . $availableSunSleeps);
                                                $availableStatus[] = 'available';
                                            } else {
                                                //print_r('Sun sleeps not available'. ' availableSunSleeps ' . $availableSunSleeps);
                                                $availableStatus[] = 'notAvailable';
                                                return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                            }

                                        } else {
                                            $availableStatus[] = 'notAvailable';
                                            return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                                        }
                                        //print_r(' Date '.$generateBookingDat.' sun_sleeps: '.session('sun_sleeps').' totalSleeps '. $totalSleeps );
                                    }

                                }
                            }

                        }

                        /* Calculating sleeps of normal booking */
                        if (!in_array($generateBookingDat, $regular_dates_array)) {

                            if ($totalSleeps < $cabinDetails->sleeps) {

                                $availableSleeps = $cabinDetails->sleeps - $totalSleeps;

                                if ($request->sleeps <= $availableSleeps) {
                                    //print_r(' Sleeps available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' availableSleeps ' . $availableSleeps);
                                    $availableStatus[] = 'available';
                                } else {
                                    //print_r(' Sleeps not available '.' Date '.$generateBookingDat.' sleeps: '.session('sleeps').' totalSleeps '. $totalSleeps . ' availableSleeps ' . $availableSleeps);
                                    $availableStatus[] = 'notAvailable';
                                    return response()->json(['error' => 'Sleeps not available on ' . $generateBookingDat], 422);
                                }

                            } else {
                                $availableStatus[] = 'notAvailable';
                                return response()->json(['error' => 'Sleeps already filled on ' . $generateBookingDat], 422);
                            }

                        }

                        /*print_r(' sleeps: '.$sleeps).'<br>';
                        print_r(' mschoolsleeps: '.$msSleeps);
                        print_r(' TotalSleeps: '.$totalSleeps);
                        print_r(' AvailableSleeps: '.$availableSleeps);*/
                    }
                } else {
                    return response()->json(['error' => 'Quota exceeded! Maximum 60 days you can book'], 422);
                }
                /* Checking bookings available end */
            }

            if (!in_array('notAvailable', $availableStatus)) {
                $available = 'success';
                //  session(['availableSuccess' => $available]);
                //   session(['requestDorms' => $request->dorms]);
                //  session(['requestBeds' => $request->beds]);
                //   session(['requestSleeps' => $request->sleeps]);
                //    session(['dateFrom' => $request->dateFrom]);
                //  session(['dateTo' => $request->dateTo]);
                //session()->flash('availableSuccess', $available);
            }
        }


        return response()->json(['available' => $available]);
    }

    /**
     * To generate date between two dates.
     *
     * @param  string $now ,$end
     * @return \Illuminate\Http\Response
     */
    protected function generateDates($now, $end)
    {
        $period = new DatePeriod(
            new DateTime($now),
            new DateInterval('P1D'),
            new DateTime($end)
        );

        return $period;
    }

    /**
     * Search available data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function calendarAvailability(TourRequest $request)
    {
        $holiday_prepare = [];
        $disableDates = [];

        if ($request->dateFrom != '') {
            $monthBegin = $request->dateFrom;
            $monthEnd = date('Y-m-t 23:59:59', strtotime($request->dateFrom));
            $seasons = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabinId))
                ->get();
            /* Cabin Details*/
            $cabinDetails = Cabin::where('is_delete', 0)
                ->where('_id', new \MongoDB\BSON\ObjectID($request->cabinId))
                ->first();
            if ($seasons) {

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

                    $prepareArray = [$dates => $day];
                    $array_unique = array_unique($holiday_prepare);
                    $array_intersect = array_intersect($prepareArray, $array_unique);

                    foreach ($array_intersect as $array_intersect_key => $array_intersect_values) {
                        $disableDates[] = $array_intersect_key;

                    }
                }
            }

        }
        return response()->json(['disableDates' => $disableDates], 201);
    }
}
