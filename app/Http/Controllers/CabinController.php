<?php

namespace App\Http\Controllers;

use App\Cabin;
use App\Userlist;
use App\Http\Requests\CabinRequest;
use Illuminate\Http\Request;

class CabinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabins = Cabin::select('_id', 'name', 'cabin_owner', 'other_cabin')
            ->where('is_delete', 0)
            ->where('other_cabin', "0")
            ->paginate(15);

        $users  = $this->users();

        return response()->json(['cabins' => $cabins, 'users' => $users], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOtherCabin()
    {
        $other_cabins = Cabin::select('_id', 'name', 'cabin_owner', 'other_cabin')
            ->where('is_delete', 0)
            ->where('other_cabin', "1")
            ->get();

        $users  = $this->users();

        return response()->json(['other_cabins' => $other_cabins, 'users' => $users], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $users  = Userlist::select('_id', 'usrFirstname', 'usrLastname')
            ->where('is_delete', 0)
            ->where('usrlId', 5)
            ->get();

        return $users;
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cabinDetails = Cabin::findOrFail($id);

        return response()->json(['cabinDetails' => $cabinDetails], 200);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  int  $type
     * @return \Illuminate\Http\Response
     */
    public function updateType(Request $request, $type, $id)
    {
        $cabin                 = Cabin::findOrFail($id);
        $cabin->other_cabin    = $type;
        $cabin->save();

        return response()->json(['message' => 'Cabin type updated'], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function assignUser(Request $request, $userId, $id)
    {
        $cabin                 = Cabin::findOrFail($id);
        $cabin->cabin_owner    = $userId;
        $cabin->save();

        return response()->json(['message' => 'User assigned successfully'], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cabin                 = Cabin::findOrFail($id);
        $cabin->is_delete      = 1;
        $cabin->save();

        return response()->json(['message' => 'Cabin deleted'], 201);
    }
}
