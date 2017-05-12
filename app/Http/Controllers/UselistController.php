<?php

namespace App\Http\Controllers;

use App\Userlist;
use Illuminate\Http\Request;

class UselistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userList = Userlist::paginate(10);
        return response($userList, 200);
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
     * @param  \App\Userlist  $userlist
     * @return \Illuminate\Http\Response
     */
    public function show(Userlist $userlist)
    {
        //
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
     * @param  \App\Userlist  $userlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Userlist $userlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Userlist  $userlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userlist $userlist)
    {
        //
    }
}
