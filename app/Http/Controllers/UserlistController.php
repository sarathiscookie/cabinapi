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
        $userList = Userlist::where('is_delete', 0)
            ->paginate(10);
        return response()->json(['userlists' => $userList], 200);
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
        $userList = Userlist::find($id);
        if(!$userList){
            return response()->json(['message' => 'User details not found'], 404);
        }
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
     * @param  \App\Http\Requests\UserlistRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserlistRequest $request, $id)
    {
        $userDetails                = Userlist::find($id);
        $userDetails->usrFirstname  = $request->input('usrFirstname');
        $userDetails->usrLastname   = $request->input('usrLastname');
        $userDetails->usrTelephone  = $request->input('usrTelephone');
        $userDetails->usrEmail      = $request->input('usrEmail');
        $userDetails->usrMobile     = $request->input('usrMobile');
        $userDetails->usrAddress    = $request->input('usrAddress');
        $userDetails->usrCity       = $request->input('usrCity');
        $userDetails->usrZip        = $request->input('usrZip');
        $userDetails->usrBirthday   = $request->input('usrBirthday');
        $userDetails->usrNewsletter = $request->input('usrNewsletter');
        $userDetails->save();
        if(!$userDetails){
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['userDetails' => $userDetails], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserlistRequest  $request
     * @param  string $statusId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(UserlistRequest $request, $statusId, $id)
    {
        $userList            = Userlist::find($id);
        $userList->usrActive = $statusId;
        $userList->save();
        if(!$userList){
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['message' => 'Status updated successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserlistRequest  $request
     * @param  string $roleId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function roleUpdate(UserlistRequest $request, $roleId, $id)
    {
        $userList            = Userlist::find($id);
        $userList->usrlId    = $roleId;
        $userList->save();
        if(!$userList){
            return response()->json(['message' => 'User not found'], 404);
        }
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
        $userList            = Userlist::find($id);
        $userList->is_delete = 1;
        $userList->save();
        if(!$userList){
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['message' => 'User deleted'], 201);
    }
}
