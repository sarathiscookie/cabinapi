<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CabinLiteContingentRequest;
use App\Http\Controllers\Controller;
use App\Cabin;
use Auth;

class ContingentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $cabin = Cabin::where('_id', $id)->where('is_delete', 0)->first();

        return view('backend.editCabinContingent', array('cabin'=>$cabin));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ContingentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CabinLiteContingentRequest $request)
    {
        $notRegular                   = 0;
        $not_regular_date             = '';
        $not_regular_beds             = 0;
        $not_regular_dorms            = 0;
        $not_regular_emergency_rooms  = 0;
        $not_regular_inquiry_guest    = 0;
        $not_regular_ms_inquiry_guest = 0;
        $not_regular_sleeps           = 0;

        $regular                      = 0;
        $mon_day                      = 0;
        $mon_beds                     = 0;
        $mon_dorms                    = 0;
        $mon_emergency_rooms          = 0;
        $mon_inquiry_guest            = 0;
        $mon_ms_inquiry_guest         = 0;
        $mon_sleeps                   = 0;

        $tue_day                      = 0;
        $tue_beds                     = 0;
        $tue_dorms                    = 0;
        $tue_emergency_rooms          = 0;
        $tue_inquiry_guest            = 0;
        $tue_ms_inquiry_guest         = 0;
        $tue_sleeps                   = 0;

        $wed_day                      = 0;
        $wed_beds                     = 0;
        $wed_dorms                    = 0;
        $wed_emergency_rooms          = 0;
        $wed_inquiry_guest            = 0;
        $wed_ms_inquiry_guest         = 0;
        $wed_sleeps                   = 0;

        $thu_day                      = 0;
        $thu_beds                     = 0;
        $thu_dorms                    = 0;
        $thu_emergency_rooms          = 0;
        $thu_inquiry_guest            = 0;
        $thu_ms_inquiry_guest         = 0;
        $thu_sleeps                   = 0;

        $fri_day                      = 0;
        $fri_beds                     = 0;
        $fri_dorms                    = 0;
        $fri_emergency_rooms          = 0;
        $fri_inquiry_guest            = 0;
        $fri_ms_inquiry_guest         = 0;
        $fri_sleeps                   = 0;

        $sat_day                      = 0;
        $sat_beds                     = 0;
        $sat_dorms                    = 0;
        $sat_emergency_rooms          = 0;
        $sat_inquiry_guest            = 0;
        $sat_ms_inquiry_guest         = 0;
        $sat_sleeps                   = 0;

        $sun_day                      = 0;
        $sun_beds                     = 0;
        $sun_dorms                    = 0;
        $sun_emergency_rooms          = 0;
        $sun_inquiry_guest            = 0;
        $sun_ms_inquiry_guest         = 0;
        $sun_sleeps                   = 0;

        /* Normal Rule */
        $sleeping_place            = (int)$request->reservation_type;
        $normal_beds               = (int)$request->normal_beds;
        $normal_dorms              = (int)$request->normal_dorms;
        $normal_emergency_rooms    = (isset($request->normal_emergency_rooms)) ? (int)$request->normal_emergency_rooms : 0;
        $normal_inquiry_guest      = (isset($request->normal_inquiry_guest)) ? (int)$request->normal_inquiry_guest : 0;
        $normal_ms_inquiry_guest   = (isset($request->normal_ms_inquiry_guest)) ? (int)$request->normal_ms_inquiry_guest : 0;
        $normal_sleeps             = ($normal_beds + $normal_dorms);

        /* Not regular Rule */
        if($request->notRegularCheckbox === '1') {
            $notRegular                    = 1;
            $not_regular_date              = $request->not_regular_date;
            $not_regular_beds              = (int)$request->not_regular_beds;
            $not_regular_dorms             = (int)$request->not_regular_dorms;
            $not_regular_emergency_rooms   = (isset($request->not_regular_emergency_rooms)) ? (int)$request->not_regular_emergency_rooms : 0;
            $not_regular_inquiry_guest     = (isset($request->not_regular_inquiry_guest)) ? (int)$request->not_regular_inquiry_guest : 0;
            $not_regular_ms_inquiry_guest  = (isset($request->not_regular_ms_inquiry_guest)) ? (int)$request->not_regular_ms_inquiry_guest : 0;
            $not_regular_sleeps            = ($not_regular_beds + $not_regular_dorms);
        }

        /* Regular rule */
        if($request->regularCheckbox === '1') {
            $regular                  = 1;
            if($request->monday === '1') {
                $mon_day              = 1;
                $mon_beds             = (int)$request->mon_beds;
                $mon_dorms            = (int)$request->mon_dorms;
                $mon_emergency_rooms  = (isset($request->mon_emergency_rooms)) ? (int)$request->mon_emergency_rooms : 0;
                $mon_inquiry_guest    = (isset($request->mon_inquiry_guest)) ? (int)$request->mon_inquiry_guest : 0;
                $mon_ms_inquiry_guest = (isset($request->mon_ms_inquiry_guest)) ? (int)$request->mon_ms_inquiry_guest : 0;
                $mon_sleeps           = ($mon_beds + $mon_dorms);
            }
            if($request->tuesday === '1') {
                $tue_day              = 1;
                $tue_beds             = (int)$request->tue_beds;
                $tue_dorms            = (int)$request->tue_dorms;
                $tue_emergency_rooms  = (isset($request->tue_emergency_rooms)) ? (int)$request->tue_emergency_rooms : 0;
                $tue_inquiry_guest    = (isset($request->tue_inquiry_guest)) ? (int)$request->tue_inquiry_guest : 0;
                $tue_ms_inquiry_guest = (isset($request->tue_ms_inquiry_guest)) ? (int)$request->tue_ms_inquiry_guest : 0;
                $tue_sleeps           = ($tue_beds + $tue_dorms);

            }
            if($request->wednesday === '1') {
                $wed_day              = 1;
                $wed_beds             = (int)$request->wed_beds;
                $wed_dorms            = (int)$request->wed_dorms;
                $wed_emergency_rooms  = (isset($request->wed_emergency_rooms)) ? (int)$request->wed_emergency_rooms : 0;
                $wed_inquiry_guest    = (isset($request->wed_inquiry_guest)) ? (int)$request->wed_inquiry_guest : 0;
                $wed_ms_inquiry_guest = (isset($request->wed_ms_inquiry_guest)) ? (int)$request->wed_ms_inquiry_guest : 0;
                $wed_sleeps           = ($wed_beds + $wed_dorms);
            }
            if($request->thursday === '1') {
                $thu_day              = 1;
                $thu_beds             = (int)$request->thu_beds;
                $thu_dorms            = (int)$request->thu_dorms;
                $thu_emergency_rooms  = (isset($request->thu_emergency_rooms)) ? (int)$request->thu_emergency_rooms : 0;
                $thu_inquiry_guest    = (isset($request->thu_inquiry_guest)) ? (int)$request->thu_inquiry_guest : 0;
                $thu_ms_inquiry_guest = (isset($request->thu_ms_inquiry_guest)) ? (int)$request->thu_ms_inquiry_guest : 0;
                $thu_sleeps           = ($thu_beds + $thu_dorms);
            }
            if($request->friday === '1') {
                $fri_day              = 1;
                $fri_beds             = (int)$request->fri_beds;
                $fri_dorms            = (int)$request->fri_dorms;
                $fri_emergency_rooms  = (isset($request->fri_emergency_rooms)) ? (int)$request->fri_emergency_rooms : 0;
                $fri_inquiry_guest    = (isset($request->fri_inquiry_guest)) ? (int)$request->fri_inquiry_guest : 0;
                $fri_ms_inquiry_guest = (isset($request->fri_ms_inquiry_guest)) ? (int)$request->fri_ms_inquiry_guest : 0;
                $fri_sleeps           = ($fri_beds + $fri_dorms);
            }
            if($request->saturday === '1') {
                $sat_day              = 1;
                $sat_beds             = (int)$request->sat_beds;
                $sat_dorms            = (int)$request->sat_dorms;
                $sat_emergency_rooms  = (isset($request->sat_emergency_rooms)) ? (int)$request->sat_emergency_rooms : 0;
                $sat_inquiry_guest    = (isset($request->sat_inquiry_guest)) ? (int)$request->sat_inquiry_guest : 0;
                $sat_ms_inquiry_guest = (isset($request->sat_ms_inquiry_guest)) ? (int)$request->sat_ms_inquiry_guest : 0;
                $sat_sleeps           = ($sat_beds + $sat_dorms);
            }
            if($request->sunday === '1') {
                $sun_day              = 1;
                $sun_beds             = (int)$request->sun_beds;
                $sun_dorms            = (int)$request->sun_dorms;
                $sun_emergency_rooms  = (isset($request->sun_emergency_rooms)) ? (int)$request->sun_emergency_rooms : 0;
                $sun_inquiry_guest    = (isset($request->sun_inquiry_guest)) ? (int)$request->sun_inquiry_guest : 0;
                $sun_ms_inquiry_guest = (isset($request->sun_ms_inquiry_guest)) ? (int)$request->sun_ms_inquiry_guest : 0;
                $sun_sleeps           = ($sun_beds + $sun_dorms);
            }
        }

        $update_array = ['sleeping_place' => $sleeping_place, 'beds' => $normal_beds, 'dormitory' => $normal_dorms, 'sleeps' => $normal_sleeps, 'ms_inquiry_starts' => $normal_ms_inquiry_guest,
            'inquiry_starts' => $normal_inquiry_guest, 'makeshift' => $normal_emergency_rooms, 'not_regular' => $notRegular, 'not_regular_date' => $not_regular_date, 'not_regular_beds' => $not_regular_beds,
            'not_regular_dorms' => $not_regular_dorms, 'not_regular_emergency_rooms' => $not_regular_emergency_rooms, 'not_regular_inquiry_guest' => $not_regular_inquiry_guest,
            'not_regular_ms_inquiry_guest' => $not_regular_ms_inquiry_guest, 'not_regular_sleeps' => $not_regular_sleeps, 'regular' => $regular,
            'mon_day' => $mon_day, 'mon_beds' => $mon_beds, 'mon_dorms' => $mon_dorms, 'mon_emergency_rooms' => $mon_emergency_rooms, 'mon_inquiry_guest' => $mon_inquiry_guest, 'mon_ms_inquiry_guest' => $mon_ms_inquiry_guest, 'mon_sleeps' => $mon_sleeps,
            'tue_day' => $tue_day, 'tue_beds' => $tue_beds, 'tue_dorms' => $tue_dorms, 'tue_emergency_rooms' => $tue_emergency_rooms, 'tue_inquiry_guest' => $tue_inquiry_guest, 'tue_ms_inquiry_guest' => $tue_ms_inquiry_guest, 'tue_sleeps' => $tue_sleeps,
            'wed_day' => $wed_day, 'wed_beds' => $wed_beds, 'wed_dorms' => $wed_dorms, 'wed_emergency_rooms' => $wed_emergency_rooms, 'wed_inquiry_guest' => $wed_inquiry_guest, 'wed_ms_inquiry_guest' => $wed_ms_inquiry_guest, 'wed_sleeps' => $wed_sleeps,
            'thu_day' => $thu_day, 'thu_beds' => $thu_beds, 'thu_dorms' => $thu_dorms, 'thu_emergency_rooms' => $thu_emergency_rooms, 'thu_inquiry_guest' => $thu_inquiry_guest, 'thu_ms_inquiry_guest' => $thu_ms_inquiry_guest, 'thu_sleeps' => $thu_sleeps,
            'fri_day' => $fri_day, 'fri_beds' => $fri_beds, 'fri_dorms' => $fri_dorms, 'fri_emergency_rooms' => $fri_emergency_rooms, 'fri_inquiry_guest' => $fri_inquiry_guest, 'fri_ms_inquiry_guest' => $fri_ms_inquiry_guest, 'fri_sleeps' => $fri_sleeps,
            'sat_day' => $sat_day, 'sat_beds' => $sat_beds, 'sat_dorms' => $sat_dorms, 'sat_emergency_rooms' => $sat_emergency_rooms, 'sat_inquiry_guest' => $sat_inquiry_guest, 'sat_ms_inquiry_guest' => $sat_ms_inquiry_guest, 'sat_sleeps' => $sat_sleeps,
            'sun_day' => $sun_day, 'sun_beds' => $sun_beds, 'sun_dorms' => $sun_dorms, 'sun_emergency_rooms' => $sun_emergency_rooms, 'sun_inquiry_guest' => $sun_inquiry_guest, 'sun_ms_inquiry_guest' => $sun_ms_inquiry_guest, 'sun_sleeps' => $sun_sleeps] ;
            Cabin::where('_id', $request->cabin_id)->update($update_array);
        return redirect()->back()->with('status', __('cabins.contingentSuccessMsgUdt'));
        // return redirect(url('/admin/cabinlite'))->with('successMsgSave', __('cabins.contingentSuccessMsgUdt'));
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
