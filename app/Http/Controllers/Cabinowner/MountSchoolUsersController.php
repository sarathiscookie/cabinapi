<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Userlist;
use App\Http\Controllers\Controller;

class MountSchoolUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cabinowner.msusers');
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
            1 => 'usrLogo',
            2 => 'usrName',
            3 => 'usrEmail',
            4 => 'usrLastname',
            5 => 'usrFirstname',

        );

        $totalData     = Userlist::where('is_delete', 0)
                        ->where('usrlId',6)->count();
        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $q             = Userlist::where('is_delete', 0)
                        ->where('usrlId',6);
        if(!empty($request->input('search.value')))
        {
            $search   = $request->input('search.value');
            $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrName', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            });
            $totalFiltered = $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrName', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            })
                ->count();
        }

        /* tfoot search functionality for email, user status and user role begin */
        if( isset($params['columns'][3]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][3]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('usrEmail', 'like', "%{$params['columns'][3]['search']['value']}%");
            })
                ->count();
        }
        /* tfoot search functionality for email, user status and user role begin */
        if( isset($params['columns'][2]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('company', 'like', "%{$params['columns'][2]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('company', 'like', "%{$params['columns'][2]['search']['value']}%");
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


        if(!empty($userLists))
        {
            foreach ($userLists as $key=> $userList)
            {


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
                if(empty($userList->company)) {
                    $user_company = $noData;
                }
                else {
                    $user_company = $userList->company;
                }

                if(empty($userList->usrAddress)) {
                    $user_addr = $noData;
                }
                else {
                    $user_addr = $userList->usrAddress;
                }


                /* Condition for last login end */

                $nestedData['usrLogo']           = '<img src="#" alt="Logo"/>';

                $nestedData['usrCompany']        = '<a class="nounderline" data-toggle="modal" data-target="#userUpdate_'.$userList->_id.'">'.$user_company.'</a><div class="modal fade" id="userUpdate_'.$userList->_id.'" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("msuserList.userUpdateModalHeading").'</h4></div><div class="responseUpdateUserMessage"></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalFirstName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_firstname_'.$userList->_id.'" type="text" value="'.$userList->usrFirstname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalLastName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_lastname_'.$userList->_id.'" type="text" value="'.$userList->usrLastname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalEmail").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_email_'.$userList->_id.'" type="text" value="'.$userList->usrEmail.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalTelephone").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_telephone_'.$userList->_id.'" type="text" value="'.$userList->usrTelephone.'"></p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalMobile").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_mobile_'.$userList->_id.'" type="text" value="'.$userList->usrMobile.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalStreet").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_address_'.$userList->_id.'" type="text" value="'.$userList->usrAddress.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalZipcode").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_zip_'.$userList->_id.'" type="text" value="'.$userList->usrZip.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalCity").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_city_'.$userList->_id.'" type="text" value="'.$userList->usrCity.'"></p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("msuserList.closeBtn").'</button></div></div></div></div>';

                $nestedData['usrEmail']       = $user_email;
                $nestedData['usrLastname']    = $last_name;
                $nestedData['usrFirstname']   = $first_name;
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
