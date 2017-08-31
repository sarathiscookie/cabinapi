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

        /* tfoot search functionality for email, user status and user role begin */
        if( isset($params['columns'][4]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][4]['search']['value']}%");
            })
                ->count();
        }

        if( isset($params['columns'][9]['search']['value']) )
        {
            $paramater = (int)$params['columns'][9]['search']['value'];
            $q->where(function($query) use ($paramater) {
                $query->where('usrlId', $paramater);
            });

            $totalFiltered = $q->where(function($query) use ($paramater) {
                $query->where('usrlId', $paramater);
            })
                ->count();
        }

        if( isset($params['columns'][10]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('usrActive', "{$params['columns'][10]['search']['value']}");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('usrActive', "{$params['columns'][10]['search']['value']}");
            })
                ->count();
        }
        /* tfoot search functionality for email, user status and user role end */

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

                if(empty($userList->usrTelephone)) {
                    $user_tel = $noData;
                }
                else {
                    $user_tel = $userList->usrTelephone;
                }

                if(empty($userList->usrMobile)) {
                    $user_mob = $noData;
                }
                else {
                    $user_mob = $userList->usrMobile;
                }

                if(empty($userList->usrCity)) {
                    $user_city = $noData;
                }
                else {
                    $user_city = $userList->usrCity;
                }

                if(empty($userList->usrZip)) {
                    $user_zip = $noData;
                }
                else {
                    $user_zip = $userList->usrZip;
                }

                if(empty($userList->usrAddress)) {
                    $user_addr = $noData;
                }
                else {
                    $user_addr = $userList->usrAddress;
                }

                /* Condition to check user details null or not end */

                /* Condition for money balance begin */
                if(empty($userList->money_balance)) {
                    $balance = $balanceNull;
                }
                else {
                    $balance = '<a class="nounderline" data-toggle="modal" data-target=".updateBalanceModel_'.$userList->_id.'"><i class="fa fa-fw fa-eur"></i>'.$userList->money_balance.'</a> <a class="btn btn-xs btn-danger deleteMoneyBalance" data-id="'.$userList->_id.'" data-money="'.$userList->money_balance.'"><i class="glyphicon glyphicon-trash"></i></a><div class="modal fade updateBalanceModel_'.$userList->_id.'" tabindex="-1" role="dialog" aria-labelledby="updateBalanceSmallModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("userList.balanceUpdateHeading").'</h4></div><div class="responseBalanceStatusMessage"></div><div class="modal-body"><div class="row"><div class="col-md-3 pull-left"><input class="form-control input-sm" id="money_balance_updated_'.$userList->_id.'" type="text" value="'.$userList->money_balance.'"></div><div class="col-md-6"><textarea class="form-control" id="money_balance_message_'.$userList->_id.'" placeholder="Reason to update balance"></textarea></div><div class="col-md-3 pull-right"><a class="btn btn-primary btn-sm balanceUpdateButton" data-id="'.$userList->_id.'">'.__("userList.balanceUpdateButton").'</a></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">'.__("userList.balanceUpdateCloseButton").'</button></div></div></div></div>';
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
                $nestedData['usrName']        = '<a class="nounderline" data-toggle="modal" data-target="#userUpdate_'.$userList->_id.'">'.$username.'</a><div class="modal fade" id="userUpdate_'.$userList->_id.'" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("userList.userUpdateModalHeading").'</h4></div><div class="responseUpdateUserMessage"></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalFirstName").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_firstname_'.$userList->_id.'" type="text" value="'.$userList->usrFirstname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalLastName").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_lastname_'.$userList->_id.'" type="text" value="'.$userList->usrLastname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalEmail").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_email_'.$userList->_id.'" type="text" value="'.$userList->usrEmail.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalTelephone").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_telephone_'.$userList->_id.'" type="text" value="'.$userList->usrTelephone.'"></p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalMobile").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_mobile_'.$userList->_id.'" type="text" value="'.$userList->usrMobile.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalStreet").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_address_'.$userList->_id.'" type="text" value="'.$userList->usrAddress.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalZipcode").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_zip_'.$userList->_id.'" type="text" value="'.$userList->usrZip.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("userList.userUpdateModalCity").'</h4><p class="list-group-item-text"><input class="form-control input-sm" id="user_city_'.$userList->_id.'" type="text" value="'.$userList->usrCity.'"></p></li></ul></div><div class="col-md-12"><button type="button" class="btn btn-block btn-primary updateUserDetails" data-button="'.$userList->_id.'">'.__("userList.userUpdateModalButton").'</button></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
                $nestedData['usrEmail']       = $user_email;
                $nestedData['money_balance']  = $balance;
                $nestedData['bookings']       = '<a class="nounderline">'.$bookingCount.'</a>';
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

        return response()->json(['deleteBalanceResponseMsg' => __('userList.deleteBalanceResponseMsg')], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function balanceUpdate(Request $request)
    {
        if($request->data_money != '' && $request->data_msg != '') {
            Userlist::where('_id', $request->data_id)
                ->update(['money_balance' => (float)$request->data_money, 'money_balance_update_message' => $request->data_msg]);

            return response()->json(['updateBalanceResponseMsg' => __('userList.updateBalanceResponseMsg')], 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userDetails                = Userlist::findOrFail($request->data_id);
        $userDetails->usrFirstname  = $request->user_firstname;
        $userDetails->usrLastname   = $request->user_lastname;
        $userDetails->usrTelephone  = $request->user_telephone;
        $userDetails->usrEmail      = $request->user_email;
        $userDetails->usrMobile     = $request->user_mobile;
        $userDetails->usrAddress    = $request->user_address;
        $userDetails->usrCity       = $request->user_city;
        $userDetails->usrZip        = $request->user_zip;
        $userDetails->save();

        return response()->json(['updateUserResponseMsg' => __('userList.userUpdateResponseMsg')], 201);
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
