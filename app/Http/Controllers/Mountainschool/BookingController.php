<?php

namespace App\Http\Controllers\Mountainschool;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bmessages;
use App\MountSchoolBooking;
use App\Userlist;

use Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('mountainschool.bookings');
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
            1 => 'invoice_number',
            2 => 'check_in',
            3 => 'reserve_to',
            4 => 'beds',
            5 => 'dormitory',
            6 => 'sleeps',
            7 => 'status'
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
                    ->where('user_id',  new \MongoDB\BSON\ObjectID(Auth::user()->_id));

                if(!empty($request->input('search.value')))
                {
                    $search   = $request->input('search.value');

                        $q->where(function($query) use ($search) {
                            $query->where('invoice_number', 'like', "%{$search}%");
                        });

                        $totalFiltered = $q->where(function($query) use ($search) {
                            $query->where('invoice_number', 'like', "%{$search}%");
                        })
                            ->count();
                    }
                /* Date range func begin */
                if($request->input('is_date_search') == 'yes')
                {
                    //if extension=mongodb.so in server use \MongoDB\BSON\UTCDateTime otherwise use MongoDate
                    $checkin_from           = explode("-", $request->input('daterange'));
                    $dateBegin              = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[0])*1000);
                    $dateEnd                = new \MongoDB\BSON\UTCDateTime(strtotime($checkin_from[1])*1000);

                    $q->whereBetween('check_in', [$dateBegin, $dateEnd]);

                    $totalFiltered = $q->whereBetween('check_in', [$dateBegin, $dateEnd])
                        ->count();
                }
                /* Date range func end */

                /* thead search functionality for booking number, email, status begin */
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

                if( isset($params['columns'][7]['search']['value']) )
                {
                    $q->where(function($query) use ($params) {
                        $query->where('status', "{$params['columns'][7]['search']['value']}");
                    });

                    $totalFiltered = $q->where(function($query) use ($params) {
                        $query->where('status', "{$params['columns'][7]['search']['value']}");
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

                        $booking->checkin_from= $checkin_from;
                        $booking->checkin_to= $reserve_to;
                        $booking->bookingStatusLabel = $bookingStatustxt;

                        $view = \View::make('mountainschool.msbookingdetailspopup', ['booking' => $booking]);
                        $popup_contents = (string)$view;
                        $invoiceNumber_comment = '<a class="nounderline" data-toggle="modal" data-target="#bookingModal_' . $booking->_id . '">' . $booking->invoice_number . '</a><div class="modal fade" id="bookingModal_' . $booking->_id . '" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content">' . $popup_contents . '</div></div></div>';



                        $checkbox        = '<input class="checked" type="checkbox" name="id[]" value="'.$booking->_id.'" />';
                        $nestedData['hash']                    = $checkbox;
                        $nestedData['invoice_number']          = $invoiceNumber_comment;
                        $nestedData['check_in']                = $checkin_from;
                        $nestedData['reserve_to']              = $reserve_to;
                        $nestedData['beds']                    = $beds;
                        $nestedData['dormitory']               = $dormitory;
                        $nestedData['sleeps']                  = $sleeps;
                        $nestedData['status']                  = $bookingStatusLabel;
                        $data[]                                = $nestedData;
                    }

            $json_data     = array(
                'draw'            => (int)$params['draw'],
                'recordsTotal'    => (int)$totalData,
                'recordsFiltered' => (int)$totalFiltered,
                'data'            => $data
            );

            return response()->json($json_data);
        }
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $userDetails = Userlist::where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();
        return view('mountainschool.newBooking', array('user' => $userDetails));
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
