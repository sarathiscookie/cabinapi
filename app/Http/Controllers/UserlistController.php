<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserlistRequest;
use App\Userlist;
use App\Booking;
use App\Role;
use DateTime;
use Illuminate\Http\Request;

class UserlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.userList');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {
        $roles = Role::where('is_delete', 0)
            ->get();

        return $roles;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params  = $request->all();

        $columns = array(
            1 => 'usrLastname',
            2 => 'usrFirstname',
            3 => 'usrName',
            4 => 'usrEmail',
            5 => 'money_balance',
            6 => 'bookings',
            7 => 'jumpto',
            8 => 'lastlogin',
            9 => 'rights',
            10 => 'actionone',
            11 => 'actiontwo',
            12 => 'usrRegistrationDate'
        );

        $totalData     = Userlist::where('is_delete', 0)->count();
        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $q             = Userlist::where('is_delete', 0);
        if(!empty($request->input('search.value')))
        {
            $search   = $request->input('search.value');
            $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            });
            $totalFiltered = $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            })
                ->count();
        }

        /* tfoot search functionality for email begin */
        if( isset($params['columns'][4]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('usrEmail', "{$params['columns'][4]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('usrEmail', "{$params['columns'][4]['search']['value']}");
            })
                ->count();
        }
        /* tfoot search functionality for email end */

        $userLists      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();
        $data          = array();
        $noData        = '<span class="label label-default">'.__("userList.noResult").'</span>';
        $balanceNull   = '<i class="fa fa-fw fa-eur"></i>00.00';

        if(!empty($userLists))
        {
            foreach ($userLists as $key=> $userList)
            {
                /* Functionality for booking count begins */
                $bookingCount  = Booking::where('is_delete', 0)
                    ->where('user', $userList->_id)
                    ->count();
                /* Functionality for booking count end */

                /* Condition to check user details null or not begin */
                if(empty($userList->usrFirstname)) {
                    $first_name = $noData;
                }
                else {
                    $first_name = $userList->usrFirstname;
                }

                if(empty($userList->usrLastname)) {
                    $last_name = $noData;
                }
                else {
                    $last_name = $userList->usrLastname;
                }

                if(empty($userList->usrName)) {
                    $username = $noData;
                }
                else {
                    $username = $userList->usrName;
                }

                if(empty($userList->usrEmail)) {
                    $user_email = $noData;
                }
                else {
                    $user_email = $userList->usrEmail;
                }
                /* Condition to check user details null or not end */

                /* Condition for money balance begin */
                if(empty($userList->money_balance)) {
                    $balance = $balanceNull;
                }
                else {
                    $balance = '<a class="nounderline modalBooking"><i class="fa fa-fw fa-eur"></i>'.$userList->money_balance.'</a> <a class="btn btn-xs btn-danger deleteMoneyBalance" data-id="'.$userList->_id.'" data-money="'.$userList->money_balance.'"><i class="glyphicon glyphicon-trash"></i></a>';
                }

                if(!empty($userList->money_balance_deleted_date)) {
                    $balance = $balanceNull. '<span class="badge">Deleted On: '.$userList->money_balance_deleted_date->format('d.m.y').'</span>';
                }
                /* Condition for money balance end */

                /* Condition for activate and deactivate button begin */
                if($userList->usrActive == '1') {
                   $actionone = '<a class="btn btn-xs btn-danger userStatus" data-id="'.$userList->_id.'" data-status="0">'.__("userList.deactivateButton").'</a>';
                }
                else {
                    $actionone = '<a class="btn btn-xs btn-success userStatus" data-id="'.$userList->_id.'" data-status="1">'.__("userList.activateButton").'</a>';
                }
                /* Condition for activate and deactivate button end */

                /* Functionality for roles begin */
                $roles      = $this->roles();

                if(count($roles)) {
                    $roleColumn = '<select class="form-control roleChange" data-id="'.$userList->_id.'">';
                    foreach ($roles as $role) {
                        if ($role->role_id == $userList->usrlId)
                            $roleSelected = 'selected = selected';
                        else
                            $roleSelected = '';
                        $roleColumn.= '<option value="'.$role->role_id.'" '.$roleSelected.' >'.$role->role_name.'</option>';
                    }
                    $roleColumn.= '</select>';
                }
                else {
                    $roleColumn = $noData;
                }
                /* Functionality for roles end */

                /* Condition for last login begin */
                if(empty($userList->lastlogin)) {
                    $lastlogin = $noData;
                }
                else {
                    $lastlogin= ($userList->lastlogin)->format('d.m.y H:i');
                }
                /* Condition for last login end */

                $nestedData['hash']           = '<input class="checked" type="checkbox" name="id[]"/>';
                $nestedData['usrLastname']    = $last_name;
                $nestedData['usrFirstname']   = $first_name;
                $nestedData['usrName']        = '<a class="nounderline modalBooking">'.$username.'</a>';
                $nestedData['usrEmail']       = $user_email;
                $nestedData['money_balance']  = $balance;
                $nestedData['bookings']       = '<a class="nounderline modalBooking">'.$bookingCount.'</a>';
                $nestedData['jumpto']         = '<i class="fa fa-fw fa-user"></i>';
                $nestedData['lastlogin']      = $lastlogin;
                $nestedData['rights']         = $roleColumn;
                $nestedData['actionone']      = $actionone;
                $nestedData['actiontwo']      = '<a href="" class="btn btn-xs btn-danger deleteUserList" data-id="'.$userList->_id.'"><i class="glyphicon glyphicon-trash"></i> '.__("userList.deleteButton").'</a>';
                $nestedData['usrRegistrationDate'] = ($userList->usrRegistrationDate)->format('d.m.y');
                $data[]                       = $nestedData;
            }
        }
        $json_data = array(
            'draw'            => (int)$params['draw'],
            'recordsTotal'    => (int)$totalData,
            'recordsFiltered' => (int)$totalFiltered,
            'data'            => $data
        );

        return response()->json($json_data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function balanceDelete(Request $request)
    {
        $date_now    = date("Y-m-d H:i:s");
        $orig_date   = new DateTime($date_now);
        $orig_date   = $orig_date->getTimestamp();
        $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);

        $deleteBalance = Userlist::where('_id', $request->data_id)
            ->update(['money_balance' => 00.00, 'money_balance_deleted' => (float)$request->data_money, 'money_balance_deleted_date' => $utcdatetime]);

        return response()->json(['deleteBalanceResponseMsg' => 'Deleted balance successfully'], 200);

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
     * @param  \App\Http\Requests\UserlistRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserlistRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userList = Userlist::findOrFail($id);

        return response()->json(['userlists' => $userList], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Userlist  $userlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Userlist $userlist)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function clubUpdate(Request $request, $id)
    {
        $clubUpdate          = Userlist::findOrFail($id);
        // Here we need data from angular. Data is an array and it is the combination of club number and club name "316148-DAV-Deutscher Alpenverein".
        //{ "usrDAV":["316148-DAV-Deutscher Alpenverein", "316149-DAV-Deutscher Alpenverein"]}
        $clubUpdate->usrDAV  = $request->input('usrDAV');
        $clubUpdate->save();

        return response()->json(['message' => 'Club added successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserlistRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserlistRequest $request, $id)
    {
        $userDetails                = Userlist::findOrFail($id);
        $userDetails->usrFirstname  = $request->input('usrFirstname');
        $userDetails->usrLastname   = $request->input('usrLastname');
        $userDetails->usrTelephone  = $request->input('usrTelephone');
        $userDetails->usrEmail      = $request->input('usrEmail');
        $userDetails->usrMobile     = $request->input('usrMobile');
        $userDetails->usrCountry    = $request->input('usrCountry');
        $userDetails->usrAddress    = $request->input('usrAddress');
        $userDetails->usrCity       = $request->input('usrCity');
        $userDetails->usrZip        = $request->input('usrZip');
        $userDetails->usrBirthday   = $request->input('usrBirthday'); //here format is date so not save as character.
        $userDetails->usrNewsletter = $request->input('usrNewsletter');
        $userDetails->save();

        return response()->json(['userDetails' => $userDetails], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Request $request)
    {
        $userList            = Userlist::findOrFail($request->data_id);
        $userList->usrActive = $request->data_status;
        $userList->save();

        return response()->json(['statusMessage' => __('userList.userStatusResponseSuccessMsg')], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roleUpdate(Request $request)
    {
        // cast to integer to avoid malicious values
        $userList            = Userlist::findOrFail($request->data_id);
        $userList->usrlId    = (int)$request->role;
        $userList->save();

        return response()->json(['roleResponseMsg' => __('userList.roleResponseMsg')], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $userList            = Userlist::findOrFail($request->data_id);
        $userList->is_delete = 1;
        $userList->save();

        return response()->json(['deleteResponseMsg' => __('userList.deleteResponseMsg')], 201);
    }
}
