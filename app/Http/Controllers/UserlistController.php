<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserlistRequest;
use App\Userlist;
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
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params  = $request->all();

        $columns = array(
            1 => 'firstname',
            2 => 'lastname',
            3 => 'username',
            4 => 'email',
            5 => 'balance',
            6 => 'bookings',
            7 => 'jumpto',
            8 => 'lastlogin',
            9 => 'rights',
            10 => 'actionone',
            11 => 'actiontwo'
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
                $query->where('usrEmail', 'like', "%{$search}%");
            });
            $totalFiltered = $q->where(function($query) use ($search) {
                $query->where('usrEmail', 'like', "%{$search}%");
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

        if(!empty($userLists))
        {
            foreach ($userLists as $key=> $userList)
            {
                $nestedData['hash']           = '<input class="checked" type="checkbox" name="id[]"/>';
                $nestedData['firstname']      = $userList->usrFirstname;
                $nestedData['lastname']       = $userList->usrLastname;
                $nestedData['username']       = $userList->usrName;
                $nestedData['email']          = $userList->usrEmail;
                $nestedData['balance']        = $userList->money_balance;
                $nestedData['bookings']       = 0;
                $nestedData['jumpto']         = 'Jump To';
                $nestedData['lastlogin']      = 'No field';
                $nestedData['rights']         = $userList->usrlId;
                $nestedData['actionone']      = 'Button';
                $nestedData['actiontwo']      = 'Button';
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
     * @param  string $statusId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Request $request, $statusId, $id)
    {
        $userList            = Userlist::findOrFail($id);
        $userList->usrActive = $statusId;
        $userList->save();

        return response()->json(['message' => 'Status updated successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $roleId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function roleUpdate(Request $request, $roleId, $id)
    {
        $userList            = Userlist::findOrFail($id);
        $userList->usrlId    = $roleId;
        $userList->save();

        return response()->json(['message' => 'Role updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userList            = Userlist::findOrFail($id);
        $userList->is_delete = 1;
        $userList->save();

        return response()->json(['message' => 'User deleted'], 201);
    }
}
