<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabin;
use App\Userlist;
use Auth;

class DetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userDetails = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrZip', 'usrCity', 'usrAddress', 'usrCountry', 'usrTelephone', 'usrMobile')
            ->where('is_delete', 0)
            ->where('_id', Auth::user()->_id)
            ->first();

        return view('cabinowner.details', ['userDetails' => $userDetails]);
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
     * @return \Illuminate\Http\Response
     */
    public function editContactInfo()
    {
        $userDetails = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrZip', 'usrCity', 'usrAddress', 'usrCountry', 'usrTelephone', 'usrMobile')
            ->where('is_delete', 0)
            ->where('_id', Auth::user()->_id)
            ->first();

        return view('cabinowner.detailsContactUpdate', ['userDetails' => $userDetails]);
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
