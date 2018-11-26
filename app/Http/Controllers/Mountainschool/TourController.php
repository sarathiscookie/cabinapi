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
use App\Traits\DateGenerate;
use App\Traits\DateFormat;

class TourController extends Controller
{
    use DateGenerate, DateFormat;

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
    public function getTourForBooking($id, $tour_index)
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
        } else {
            $tours->basic_settings = [
                'contact_person' => '',
                'no_guides' => 0,
                'half_board' => 0,
                'beds' => 0,
                'sleeps' => 0,
                'dorms' => 0,
                'guests' => 0
            ];
        }

        return view('mountainschool.getTourCabin', [
            'tour'       => $tours,
            'tour_index' => $tour_index,
            'no_cabins'  => $tours->no_cabins
        ]);
    }

    public function getToursForBooking()
    {
        return request()->all();
    }
}
