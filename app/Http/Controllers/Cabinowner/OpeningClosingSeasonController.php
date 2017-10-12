<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OpenCloseRequest;
use App\Cabin;
use Auth;

class OpeningClosingSeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabin = Cabin::select('summerSeason', 'summerSeasonStatus', 'earliest_summer_open', 'earliest_summer_close', 'latest_summer_open', 'latest_summer_close', 'summer_mon', 'summer_tue', 'summer_wed', 'summer_thu', 'summer_fri', 'summer_sat', 'summer_sun', 'winterSeason', 'winterSeasonStatus', 'earliest_winter_open', 'earliest_winter_close', 'latest_winter_open', 'latest_winter_close', 'winter_mon', 'winter_tue', 'winter_wed', 'winter_thu', 'winter_fri', 'winter_sat', 'winter_sun')
            ->where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        return view('cabinowner.openCloseTimeSeason', ['cabin' => $cabin]);
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OpenCloseRequest
     * @return \Illuminate\Http\Response
     */
    public function summerUpdate(OpenCloseRequest $request)
    {
        if($request->season == 'summer') {
            $summerSeason          = $request->summerSeason;
            $summerSeasonStatus    = $request->summerSeasonStatus;
            $earliest_summer_open  = $request->earliest_summer_open;
            $earliest_summer_close = $request->earliest_summer_close;
            $latest_summer_open    = $request->latest_summer_open;
            $latest_summer_close   = $request->latest_summer_close;
            $summer_mon            = ($request->summer_mon == '1') ? (int)$request->summer_mon : 0;
            $summer_tue            = ($request->summer_tue == '1') ? (int)$request->summer_tue : 0;
            $summer_wed            = ($request->summer_wed == '1') ? (int)$request->summer_wed : 0;
            $summer_thu            = ($request->summer_thu == '1') ? (int)$request->summer_thu : 0;
            $summer_fri            = ($request->summer_fri == '1') ? (int)$request->summer_fri : 0;
            $summer_sat            = ($request->summer_sat == '1') ? (int)$request->summer_sat : 0;
            $summer_sun            = ($request->summer_sun == '1') ? (int)$request->summer_sun : 0;

            Cabin::where('is_delete', 0)
                ->where('cabin_owner', Auth::user()->_id)
                ->update(['summerSeason' => $summerSeason, 'summerSeasonStatus' => $summerSeasonStatus, 'earliest_summer_open' => $earliest_summer_open, 'earliest_summer_close' => $earliest_summer_close, 'latest_summer_open' => $latest_summer_open, 'latest_summer_close' => $latest_summer_close, 'summer_mon' => $summer_mon, 'summer_tue' => $summer_tue, 'summer_wed' => $summer_wed, 'summer_thu' => $summer_thu, 'summer_fri' => $summer_fri, 'summer_sat' => $summer_sat, 'summer_sun' => $summer_sun]);

            return redirect()->back()->with('successSummer', 'Summer opening and closing time updated successfully!');
        }
        else {
            return redirect()->back()->with('failureSummer', 'Something went wrong!');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OpenCloseRequest
     * @return \Illuminate\Http\Response
     */
    public function winterUpdate(OpenCloseRequest $request)
    {
        if($request->season == 'winter') {
            $winterSeason          = $request->winterSeason;
            $winterSeasonStatus    = $request->winterSeasonStatus;
            $earliest_winter_open  = $request->earliest_winter_open;
            $earliest_winter_close = $request->earliest_winter_close;
            $latest_winter_open    = $request->latest_winter_open;
            $latest_winter_close   = $request->latest_winter_close;
            $winter_mon            = ($request->winter_mon == '1') ? (int)$request->winter_mon : 0;
            $winter_tue            = ($request->winter_tue == '1') ? (int)$request->winter_tue : 0;
            $winter_wed            = ($request->winter_wed == '1') ? (int)$request->winter_wed : 0;
            $winter_thu            = ($request->winter_thu == '1') ? (int)$request->winter_thu : 0;
            $winter_fri            = ($request->winter_fri == '1') ? (int)$request->winter_fri : 0;
            $winter_sat            = ($request->winter_sat == '1') ? (int)$request->winter_sat : 0;
            $winter_sun            = ($request->winter_sun == '1') ? (int)$request->winter_sun : 0;

            Cabin::where('is_delete', 0)
                ->where('cabin_owner', Auth::user()->_id)
                ->update(['winterSeason' => $winterSeason, 'winterSeasonStatus' => $winterSeasonStatus, 'earliest_winter_open' => $earliest_winter_open, 'earliest_winter_close' => $earliest_winter_close, 'latest_winter_open' => $latest_winter_open, 'latest_winter_close' => $latest_winter_close, 'winter_mon' => $winter_mon, 'winter_tue' => $winter_tue, 'winter_wed' => $winter_wed, 'winter_thu' => $winter_thu, 'winter_fri' => $winter_fri, 'winter_sat' => $winter_sat, 'winter_sun' => $winter_sun]);

            return redirect()->back()->with('successWinter', 'Winter opening and closing time updated successfully!');
        }
        else {
            return redirect()->back()->with('failureWinter', 'Something went wrong!');
        }

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
