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
            0 => 'usrLogo',
            1 => 'company',
            2 => 'usrEmail',
            3 => 'usrLastname',
            4 => 'usrFirstname' );
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
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            });
            $totalFiltered = $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%")
                    ->orWhere('usrFirstname', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('usrLastname', 'like', "%{$search}%");
            })
                ->count();
        }
        /*  Search functionality query starts here */
        /*   search  with user email  begin */
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
        /*  search  with user company begin */
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
        /*  Search functionality  query ends here */

        $userLists      = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();
        $data          = array();



        if(!empty($userLists))
        {
            foreach ($userLists as $key=> $userList)
            {

                $user_company =   $this->filterOutptVariables($userList->company);
                $nestedData['usrLogo']           = '<img src="#" alt="Logo"/>';
                //--------------------------
                $view = \View::make('cabinowner.msuserdetailspopup', ['userList' => $userList]);
                $contents = (string) $view;

              //  $nestedData['usrCompany']        = '<a class="nounderline" data-toggle="modal" data-target="#userUpdate_'.$userList->_id.'">'.$user_company.'</a><div class="modal fade" id="userUpdate_'.$userList->_id.'" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'.__("msuserList.userUpdateModalHeading").'</h4></div><div class="responseUpdateUserMessage"></div><div class="modal-body"><div class="row"><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalFirstName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_firstname_'.$userList->_id.'" type="text" value="'.$userList->usrFirstname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalLastName").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_lastname_'.$userList->_id.'" type="text" value="'.$userList->usrLastname.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalEmail").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_email_'.$userList->_id.'" type="text" value="'.$userList->usrEmail.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalTelephone").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_telephone_'.$userList->_id.'" type="text" value="'.$userList->usrTelephone.'"></p></li></ul></div><div class="col-md-6"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalMobile").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_mobile_'.$userList->_id.'" type="text" value="'.$userList->usrMobile.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalStreet").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_address_'.$userList->_id.'" type="text" value="'.$userList->usrAddress.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalZipcode").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_zip_'.$userList->_id.'" type="text" value="'.$userList->usrZip.'"></p></li><li class="list-group-item"><h4 class="list-group-item-heading">'.__("msuserList.userUpdateModalCity").'</h4><p class="list-group-item-text"><input readonly class="form-control input-sm" id="user_city_'.$userList->_id.'" type="text" value="'.$userList->usrCity.'"></p></li></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.__("msuserList.closeBtn").'</button></div></div></div></div>';
                $nestedData['usrCompany']     ='<a class="nounderline" data-toggle="modal" data-target="#userUpdate_'.$userList->_id.'">'.$user_company.'</a><div class="modal fade" id="userUpdate_'.$userList->_id.'" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel"><div class="modal-dialog"><div class="modal-content"> '.$contents.'</div></div></div>';
                $nestedData['usrEmail']       =  $this->filterOutptVariables($userList->usrEmail);
                $nestedData['usrLastname']    =  $this->filterOutptVariables($userList->usrLastname);
                $nestedData['usrFirstname']   =  $this->filterOutptVariables($userList->usrFirstname);
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
    /*
     * filterOutptVariables for filter the variables if data is exits or not
     * * @return \Illuminate\Http\Response
     */
    function filterOutptVariables($userData){
        $noData        = '<span class="label label-default">'.__("msuserList.noResult").'</span>';
        if(empty($userData))  {
            $value= $noData;
        }
        else {
            $value =$userData;
        }
        return      $value;
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