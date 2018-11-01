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
            $test                  = [];
            $clickHere             = '<a href="/inquiry">click here</a>';
            for ($tb = 0; $tb <count($request->get('ind_tour_no')); $tb++) {
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
                    $bedsRequest         = (int)$request->$beds[$tb];
                    $dormsRequest        = (int)$request->$dormitory[$tb];
                    $sleepsRequest       = (int)$request->$sleeps[$tb];
                    $requestBedsSumDorms = (int)$request->$beds[$tb] + (int)$request->$dormitory[$tb];

                    /*if($monthBegin < $monthEnd) {
                        $test[] = $dateDifference->days;
                    }
                    else {
                        //return response()->json(['failureMsg' =>  __('tours.dateGreater')]);
                        return response()->json(['error' => __("tours.dateGreater"), 'bookingOrder' => $i], 422);
                    }*/

                    // Cabin Details
                    $cabinDetails       = Cabin::where('is_delete', 0)
                        ->where('_id', new \MongoDB\BSON\ObjectID($request->$cabinId[$tb]))
                        ->first();

                    // If cabin is a registered cabin then booking data store in to database
                    if($cabinDetails->other_cabin === '0') {
                        // Generate auto number and create invoice number
                        if (!empty($cabinDetails->invoice_autonum)) {
                            $autoNumber = (int)$cabinDetails->invoice_autonum + 1;
                        }
                        else {
                            $autoNumber = 100000;
                        }

                        if (!empty($cabinDetails->invoice_code)) {
                            $invoiceCode   = $cabinDetails->invoice_code;
                            $invoiceNumber = $invoiceCode . "-" . date("y") . "-" . $autoNumber;
                        }

                        // Generate dates b/w checking from and to
                        /* $generateBookingDates = $this->generateDates($request->$check_in[$tb]->format('Y-m-d'), $request->$check_out[$tb]->format('Y-m-d'));

                         foreach ($generateBookingDates as $generateBookingDate) {
                             $dates                = $generateBookingDate->format('Y-m-d');
                             $day                  = $generateBookingDate->format('D');

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
                                 $sleeps        = $bookings->sum('sleeps');
                                 $beds          = $bookings->sum('beds');
                                 $dorms         = $bookings->sum('dormitory');
                             }
                             else {
                                 $dorms         = 0;
                                 $beds          = 0;
                                 $sleeps        = 0;
                             }

                             if(count($msBookings) > 0) {
                                 $msSleeps      = $msBookings->sum('sleeps');
                                 $msBeds        = $msBookings->sum('beds');
                                 $msDorms       = $msBookings->sum('dormitory');
                             }
                             else {
                                 $msSleeps      = 0;
                                 $msBeds        = 0;
                                 $msDorms       = 0;
                             }

                             // Taking beds, dorms and sleeps depends up on sleeping_place
                             if($cabinDetails->sleeping_place != 1) {
                                 $totalBeds           = $beds + $msBeds;
                                 $totalDorms          = $dorms + $msDorms;
                             }
                             else {
                                 $totalSleeps = $sleeps + $msSleeps;
                             }


                         }*/







                        // Getting tour details
                        $tourName = Tour::select('tour_name')
                            ->where('is_delete', 0)
                            ->where('status', 1)
                            ->where('user_id', Auth::user()->_id)
                            ->where('_id', new \MongoDB\BSON\ObjectID($request->tour_name))
                            ->first();

                        $storeBooking                 = new MountSchoolBooking;
                        $storeBooking->tour_name      = $tourName->tour_name;
                        $storeBooking->ind_tour_no    = $request->ind_tour_no[$tb];
                        $storeBooking->tour_guide     = $request->tour_guide[$tb];
                        $storeBooking->ind_notice     = $request->ind_notice;
                        $storeBooking->no_guides      = (int)$request->$no_guides[$tb];
                        $storeBooking->guests         = (int)$request->$guests[$tb];
                        $storeBooking->cabin_name     = $cabinDetails->name;
                        $storeBooking->cabin_id       = new \MongoDB\BSON\ObjectID($cabinDetails->_id);
                        $storeBooking->check_in       = $this->getDateUtc($request->$check_in[$tb]);
                        $storeBooking->reserve_to     = $this->getDateUtc($request->$check_out[$tb]);
                        $storeBooking->is_delete      = 0;
                        $storeBooking->bookingdate    = date('Y-m-d H:i:s');
                        $storeBooking->half_board     = (isset($request->$halfboard[$tb])) ? $request->$halfboard[$tb] : '0';
                        $storeBooking->other_cabin    = $cabinDetails->other_cabin;
                        $storeBooking->invoice_number = $invoiceNumber;
                        $storeBooking->sleeps         = ($cabinDetails->sleeping_place === 1) ? $sleepsRequest : $requestBedsSumDorms;
                        $storeBooking->beds           = ($cabinDetails->sleeping_place != 1) ? $bedsRequest : 0;
                        $storeBooking->dormitory      = ($cabinDetails->sleeping_place != 1) ? $dormsRequest : 0;
                        $storeBooking->status         = 1;
                        $storeBooking->user_id        = new \MongoDB\BSON\ObjectID(Auth::user()->_id);
                        $storeBooking->save();

                        // Update cabin invoice_autonum begin
                        if($storeBooking) {
                            Cabin::where('is_delete', 0)
                                ->where('other_cabin', "0")
                                ->where('_id', new \MongoDB\BSON\ObjectID($cabinDetails->_id))
                                ->update(['invoice_autonum' => $autoNumber]);
                        }

                    }
                }
            }

            return response()->json(['successMsg' =>  __('tours.successMsgBooking')], 200);
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
