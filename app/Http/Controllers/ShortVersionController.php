<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabin;
use App\Userlist;

class ShortVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.shortversion');
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
            0 => 'invoice_code',
            1 => 'name',
            2 => 'usrEmail',
            3 => 'usrName',
            4 => 'usrLastname',
            5 => 'usrFirstname',

        );

        $totalData     = Cabin::where('is_delete', 0)
                            ->where('other_cabin','0')->count();
        $totalFiltered = $totalData;
        $limit         = (int)$request->input('length');
        $start         = (int)$request->input('start');
        $order         = $columns[$params['order'][0]['column']]; //contains column index
        $dir           = $params['order'][0]['dir']; //contains order such as asc/desc

        $q             = Cabin::where('is_delete', 0)
                            ->where('other_cabin','0');


        $userq             = Userlist::where('is_delete', 0)
                                ->where('usrlId',5);

        if(!empty($request->input('search.value')))
        {
            $search   = $request->input('search.value');

            $userq->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrName', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            });
            $users = $userq->first();

             $totalFiltered = $userq->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('usrName', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            })
                ->count();
            if($totalFiltered>0)
                $q ->where('cabin_owner', $users->_id);
           else
            {

                $q->where(function($query) use ($search) {
                    $query->where('invoice_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");

                });
            }

        }

        /* tfoot search functionality for email begin */

        if( isset($params['columns'][2]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('invoice_code', 'like', "%{$params['columns'][2]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('invoice_code', 'like', "%{$params['columns'][2]['search']['value']}%");
            })
                ->count();
        }
        /* tfoot search functionality for email, user status and user role begin */
       if( isset($params['columns'][3]['search']['value']) )
        {
            $q->where(function($query) use ($params) {
                $query->where('name', 'like', "%{$params['columns'][3]['search']['value']}%");
            });

            $totalFiltered = $q->where(function($query) use ($params) {
                $query->where('name', 'like', "%{$params['columns'][3]['search']['value']}%");
            })
                ->count();
        }



        /* tfoot search functionality for email, user status and user role end */

        $cabinLists      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();
        $data          = array();
        $noData        = '<span class="label label-default">'.__("shortVersin.noResult").'</span>';


        if(!empty($cabinLists))
        {
            foreach ($cabinLists as $key=> $cabinList)
            {


                /* Condition to check user details null or not begin */
                if(empty($cabinList->name)) {
                    $cabin_name = $noData;
                }
                else {
                    $cabin_name = $cabinList->name;
                }

                if(empty($cabinList->invoice_code)) {
                    $shortversion = $noData;
                }
                else {
                    $shortversion = $cabinList->invoice_code;
                }



                            $users = Userlist::select('usrName','usrFirstname', 'usrLastname', 'usrEmail', 'usrAddress', 'usrTelephone', 'usrMobile','usrZip','usrCity')
                                ->where('_id', $cabinList->cabin_owner)
                                ->get();
                            foreach ($users as $user){
                                $usrEmail   = $user->usrEmail;
                                if(empty($user->usrCity)) {
                                    $user_city = $noData;
                                }
                                else {
                                    $user_city = $user->usrCity;
                                }

                                if(empty($user->usrZip)) {
                                    $user_zip = $noData;
                                }
                                else {
                                    $user_zip = $user->usrZip;
                                }

                                if(empty($user->usrAddress)) {
                                    $user_addr = $noData;
                                }
                                else {
                                    $user_addr = $user->usrAddress;
                                }
                                if(empty($user->usrFirstname)) {
                                    $usrFirstname = $noData;
                                }
                                else {
                                    $usrFirstname = $user->usrFirstname;
                                }
                                if(empty($user->usrLastname)) {
                                    $usrLastname = $noData;
                                }
                                else {
                                    $usrLastname = $user->usrLastname;
                                }
                                if(empty($user->usrTelephone)) {
                                    $usrTelephone = $noData;
                                }
                                else {
                                    $usrTelephone = $user->usrTelephone;
                                }
                                if(empty($user->usrMobile)) {
                                    $usrMobile = $noData;
                                }
                                else {
                                    $usrMobile = $user->usrMobile;
                                }
                                if(empty($user->usrName)) {
                                    $usrName = $noData;
                                }
                                else {
                                    $usrName = $user->usrName;
                                }

                            }

                        /* Condition for checking who booked bookings end*/
                $nestedData['shortversion'] = $shortversion;
                $nestedData['cabinname'] = $cabin_name;
                $nestedData['usrEmail'] = $usrEmail;
                $nestedData['usrName']        = '<a class="nounderline" data-toggle="modal" data-target="#userUpdate_'.$user->_id.'">'.$usrName.'</a><div class="modal fade" id="userUpdate_'.$user->_id.'" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("cabins.userUpdateModalHeading").'</h4></div><div class="responseUpdateUserMessage"></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalFirstName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_firstname_'.$user->_id.'" type="text" value="'.$user->usrFirstname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalLastName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_lastname_'.$user->_id.'" type="text" value="'.$user->usrLastname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalEmail").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_email_'.$user->_id.'" type="text" value="'.$user->usrEmail.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalTelephone").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_telephone_'.$user->_id.'" type="text" value="'.$user->usrTelephone.'"></p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalMobile").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_mobile_'.$user->_id.'" type="text" value="'.$user->usrMobile.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalStreet").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_address_'.$user->_id.'" type="text" value="'.$user->usrAddress.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalZipcode").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_zip_'.$user->_id.'" type="text" value="'.$user->usrZip.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("cabins.userUpdateModalCity").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_city_'.$user->_id.'" type="text" value="'.$user->usrCity.'"></p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("cabins.closeBtn").'</button></div></div></div></div>';
                $nestedData['usrFirstname'] = $usrFirstname;
                $nestedData['usrLastname'] = $usrLastname;
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
