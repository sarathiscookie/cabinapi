<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cabinowner.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params        = $request->all();

        $columns       = array(
            1 => 'invoice_number',
            2 => 'usrEmail',
            3 => 'checkin_from',
            4 => 'reserve_to',
            5 => 'beds',
            6 => 'dormitory',
            7 => 'sleeps',
            8 => 'status',
            9 => 'payment_status',
            10 => 'payment_type',
            11 => 'total_prepayment_amount',
            12 => 'txid'
        );

        $totalData     = Booking::where('is_delete', 0)->count();

        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $users         = Userlist::select('_id', 'usrEmail')
            ->where('is_delete', 0)
            ->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][2]['search']['value']}%");
            })
            ->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        $bookings      = Booking::select('_id', 'invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'cabinname', 'reference_no', 'clubmember', 'bookingdate', 'txid')
            ->where('is_delete', 0)
            ->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();
        $data          = array();

        $json_data     = array(
            'draw'            => (int)$params['draw'],
            'recordsTotal'    => (int)$totalData,
            'recordsFiltered' => (int)$totalFiltered,
            'data'            => $data
        );

        return response()->json($json_data);
        //echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($params, $start, $order, $dir, $limit)
    {
        $users     = Userlist::select('_id', 'usrEmail')
            ->where('is_delete', 0)
            ->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][2]['search']['value']}%");
            })
            ->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        if(count($users) > 0) {
            foreach ($users as $user) {
                $bookings = Booking::select('_id', 'invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'cabinname', 'reference_no', 'clubmember', 'bookingdate', 'txid')
                    ->where('is_delete', 0)
                    ->where('user', $user->_id)
                    ->skip($start)
                    ->take($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Booking::select('_id', 'invoice_number', 'temp_user_id', 'user', 'checkin_from', 'reserve_to', 'beds', 'dormitory', 'sleeps', 'status', 'payment_status', 'payment_type', 'total_prepayment_amount', 'cabinname', 'reference_no', 'clubmember', 'bookingdate', 'txid')
                    ->where('is_delete', 0)
                    ->count();
            }
        }
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
    public function show($id, $key)
    {
        $users = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrAddress', 'usrTelephone', 'usrMobile')
            ->where('_id', $id)
            ->get();
        foreach ($users as $user){
            $usrEmail                       = $user->usrEmail;
            $bookings[$key]['usrEmail']     = $usrEmail;
            $bookings[$key]['usrFirstname'] = $user->usrFirstname;
            $bookings[$key]['usrLastname']  = $user->usrLastname;
            $bookings[$key]['usrAddress']   = $user->usrAddress;
            $bookings[$key]['usrTelephone'] = $user->usrTelephone;
            $bookings[$key]['usrMobile']    = $user->usrMobile;
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
        $booking = Booking::findOrFail($id);

        return response()->json(['booking' => $booking], 200);
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
