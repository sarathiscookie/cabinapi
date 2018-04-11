<?php

namespace App\Http\Controllers;
/*
use Illuminate\Http\Request;
use App\Http\Requests\CabinLiteOpenCloseRequest;
use App\Season;
use Carbon\Carbon;
use DateTime;
use App\Cabin;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth;
*/

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CabinLiteOpenCloseRequest;
use App\Season;
use Auth;
use Carbon\Carbon;
use DateTime;
use App\Cabin;
class CabinLiteOpenCloseSeasonController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index($id)
    {
        $seasons = Season::select('_id', 'summerSeason', 'summerSeasonYear', 'summerSeasonStatus', 'earliest_summer_open', 'earliest_summer_close', 'latest_summer_open', 'latest_summer_close', 'summer_next_season', 'summer_mon', 'summer_tue', 'summer_wed', 'summer_thu', 'summer_fri', 'summer_sat', 'summer_sun', 'winterSeason', 'winterSeasonYear', 'winterSeasonStatus', 'earliest_winter_open', 'earliest_winter_close', 'latest_winter_open', 'latest_winter_close', 'winter_next_season', 'winter_mon', 'winter_tue', 'winter_wed', 'winter_thu', 'winter_fri', 'winter_sat', 'winter_sun')

            ->where('cabin_id',    new \MongoDB\BSON\ObjectID($id))
            ->get();
        $cabin  = Cabin::where('_id', $id)->first();

        return view('backend.cabinSeasonDetails', ['seasons' => $seasons  , 'cabin'=>$cabin ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cabinowner.openCloseSeasonCreate');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSummer()
    {
        return view('cabinowner.openCloseSummerCreate');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWinter()
    {
        return view('cabinowner.openCloseWinterCreate');
    }

    /**
     * To generate date format as mongo.
     *
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    protected function getDateUtc($date){

        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime         = new DateTime($dateFormatChange);
        $timeStamp        = $dateTime->getTimestamp();
        $utcDateTime      = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);
        return $utcDateTime;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CabinLiteOpenCloseRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CabinLiteOpenCloseRequest $request)
    {

            //summerSeason/winterSeason hidden field with value 1 /storeSeason pass by ajax arg
            if (isset($request->storeSeason) && $request->summerSeason == '1' && $request->winterSeason == '1') {


                $season = new Season;

                /* Summer season */
                $season->summerSeason          = 1; // 1 - Enabled, 0 - Disabled
                $season->summerSeasonYear      = (int)$request->summerSeasonYear;
                $season->summerSeasonStatus    = $request->summerSeasonStatus;
                $season->earliest_summer_open  = $this->getDateUtc($request->earliest_summer_open);
                $season->earliest_summer_close = $this->getDateUtc($request->earliest_summer_close);
                $season->latest_summer_open    = $this->getDateUtc($request->latest_summer_open);
                $season->latest_summer_close   = $this->getDateUtc($request->latest_summer_close);
                $season->summer_next_season    = $this->getDateUtc($request->summer_next_season);
                $season->summer_mon            = ($request->summer_mon == '1') ? (int)$request->summer_mon : 0;
                $season->summer_tue            = ($request->summer_tue == '1') ? (int)$request->summer_tue : 0;
                $season->summer_wed            = ($request->summer_wed == '1') ? (int)$request->summer_wed : 0;
                $season->summer_thu            = ($request->summer_thu == '1') ? (int)$request->summer_thu : 0;
                $season->summer_fri            = ($request->summer_fri == '1') ? (int)$request->summer_fri : 0;
                $season->summer_sat            = ($request->summer_sat == '1') ? (int)$request->summer_sat : 0;
                $season->summer_sun            = ($request->summer_sun == '1') ? (int)$request->summer_sun : 0;


                $season->winterSeason          = 1; // 1 - Enabled, 0 - Disabled
                $season->winterSeasonYear      = (int)$request->winterSeasonYear;
                $season->winterSeasonStatus    = $request->winterSeasonStatus;
                $season->earliest_winter_open  = $this->getDateUtc($request->earliest_winter_open);
                $season->earliest_winter_close = $this->getDateUtc($request->earliest_winter_close);
                $season->latest_winter_open    = $this->getDateUtc($request->latest_winter_open);
                $season->latest_winter_close   = $this->getDateUtc($request->latest_winter_close);
                $season->winter_next_season    = $this->getDateUtc($request->winter_next_season);
                $season->winter_mon            = ($request->winter_mon == '1') ? (int)$request->winter_mon : 0;
                $season->winter_tue            = ($request->winter_tue == '1') ? (int)$request->winter_tue : 0;
                $season->winter_wed            = ($request->winter_wed == '1') ? (int)$request->winter_wed : 0;
                $season->winter_thu            = ($request->winter_thu == '1') ? (int)$request->winter_thu : 0;
                $season->winter_fri            = ($request->winter_fri == '1') ? (int)$request->winter_fri : 0;
                $season->winter_sat            = ($request->winter_sat == '1') ? (int)$request->winter_sat : 0;
                $season->winter_sun            = ($request->winter_sun == '1') ? (int)$request->winter_sun : 0;

              $cabin = Cabin::select('_id', 'cabin_owner')
                    ->where('_id', $request->cabin_id)
                    ->first();

                $season->cabin_owner = new \MongoDB\BSON\ObjectID($cabin->cabin_owner) ;
                $season->cabin_id =  new \MongoDB\BSON\ObjectID($request->cabin_id);
                $season->save();

                echo json_encode(array('successMsgSeasonSave' => __('cabins.successMsgSeasonSave')));

            }else{
                echo json_encode(array('errorMsg' => __('cabins.failure')));
            }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lists(Request $request)
    {

        $seasons = Season::select('_id', 'summerSeason', 'summerSeasonYear', 'summerSeasonStatus', 'earliest_summer_open', 'earliest_summer_close', 'latest_summer_open', 'latest_summer_close', 'summer_next_season', 'summer_mon', 'summer_tue', 'summer_wed', 'summer_thu', 'summer_fri', 'summer_sat', 'summer_sun', 'winterSeason', 'winterSeasonYear', 'winterSeasonStatus', 'earliest_winter_open', 'earliest_winter_close', 'latest_winter_open', 'latest_winter_close', 'winter_next_season', 'winter_mon', 'winter_tue', 'winter_wed', 'winter_thu', 'winter_fri', 'winter_sat', 'winter_sun')

            ->where('cabin_id',    new \MongoDB\BSON\ObjectID($request->cabin_id))
            ->get();


        return view('backend.listCabinSeason', ['seasons' => $seasons   ] );
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
    public function editSummer(Request $request)
    {
       $summerSeason = Season::select('_id', 'summerSeason', 'summerSeasonYear', 'summerSeasonStatus', 'earliest_summer_open', 'earliest_summer_close', 'latest_summer_open', 'latest_summer_close', 'summer_next_season', 'summer_mon', 'summer_tue', 'summer_wed', 'summer_thu', 'summer_fri', 'summer_sat', 'summer_sun')
            ->where('summerSeason', 1)
            ->where('_id',  $request->season_id)
            ->first();

        if(count($summerSeason) > 0) {
            return view('backend.editCabinSeason', ['summerSeason' => $summerSeason]);
        }

     //  return view('backend.editCabinSeason' );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editWinter(Request $request)
    {
        $winterSeason = Season::select('_id', 'winterSeason', 'winterSeasonYear', 'winterSeasonStatus', 'earliest_winter_open', 'earliest_winter_close', 'latest_winter_open', 'latest_winter_close', 'winter_next_season', 'winter_mon', 'winter_tue', 'winter_wed', 'winter_thu', 'winter_fri', 'winter_sat', 'winter_sun')
            ->where('winterSeason', 1)
            ->where('_id',  $request->season_id)
            ->first();

        if(count($winterSeason) > 0) {
            return view('backend.editCabinSeasonWinter', ['winterSeason' => $winterSeason]);
        }

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
    public function updateSummer(CabinLiteOpenCloseRequest $request)
    {



        if(isset($request->updateSummerSeason) && $request->summerSeasonId != '') {
                $summerSeasonId = $request->summerSeasonId;
                $summerSeasonYear = (int)$request->summerSeasonYear;
                $summerSeasonStatus = $request->summerSeasonStatus;
                $earliest_summer_open = $this->getDateUtc($request->earliest_summer_open);
                $earliest_summer_close = $this->getDateUtc($request->earliest_summer_close);
                $latest_summer_open = $this->getDateUtc($request->latest_summer_open);
                $latest_summer_close = $this->getDateUtc($request->latest_summer_close);
                $summer_next_season = $this->getDateUtc($request->summer_next_season);
                $summer_mon = ($request->summer_mon == '1') ? (int)$request->summer_mon : 0;
                $summer_tue = ($request->summer_tue == '1') ? (int)$request->summer_tue : 0;
                $summer_wed = ($request->summer_wed == '1') ? (int)$request->summer_wed : 0;
                $summer_thu = ($request->summer_thu == '1') ? (int)$request->summer_thu : 0;
                $summer_fri = ($request->summer_fri == '1') ? (int)$request->summer_fri : 0;
                $summer_sat = ($request->summer_sat == '1') ? (int)$request->summer_sat : 0;
                $summer_sun = ($request->summer_sun == '1') ? (int)$request->summer_sun : 0;
                Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabin_id))
                ->where('_id', new \MongoDB\BSON\ObjectID($summerSeasonId))
                    ->update(['summerSeasonYear' => $summerSeasonYear, 'summerSeasonStatus' => $summerSeasonStatus, 'earliest_summer_open' => $earliest_summer_open, 'earliest_summer_close' => $earliest_summer_close, 'latest_summer_open' => $latest_summer_open, 'latest_summer_close' => $latest_summer_close, 'summer_next_season' => $summer_next_season, 'summer_mon' => $summer_mon, 'summer_tue' => $summer_tue, 'summer_wed' => $summer_wed, 'summer_thu' => $summer_thu, 'summer_fri' => $summer_fri, 'summer_sat' => $summer_sat, 'summer_sun' => $summer_sun]);

                echo json_encode(array('successMsgSeasonSave' => __('cabins.successMsgSeasonUpt')));
            }
        else {
                echo json_encode(array('errorMsg' => __('cabins.failure')));
            }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OpenCloseRequest
     * @return \Illuminate\Http\Response
     */
    public function updateWinter(CabinLiteOpenCloseRequest $request)
    {
      if(isset($request->updateWinterSeason) && $request->winterSeasonId != '') {
            $winterSeasonId        = $request->winterSeasonId;
            $winterSeasonYear      = (int)$request->winterSeasonYear;
            $winterSeasonStatus    = $request->winterSeasonStatus;
            $earliest_winter_open  = $this->getDateUtc($request->earliest_winter_open);
            $earliest_winter_close = $this->getDateUtc($request->earliest_winter_close);
            $latest_winter_open    = $this->getDateUtc($request->latest_winter_open);
            $latest_winter_close   = $this->getDateUtc($request->latest_winter_close);
            $winter_next_season    = $this->getDateUtc($request->winter_next_season);
            $winter_mon            = ($request->winter_mon == '1') ? (int)$request->winter_mon : 0;
            $winter_tue            = ($request->winter_tue == '1') ? (int)$request->winter_tue : 0;
            $winter_wed            = ($request->winter_wed == '1') ? (int)$request->winter_wed : 0;
            $winter_thu            = ($request->winter_thu == '1') ? (int)$request->winter_thu : 0;
            $winter_fri            = ($request->winter_fri == '1') ? (int)$request->winter_fri : 0;
            $winter_sat            = ($request->winter_sat == '1') ? (int)$request->winter_sat : 0;
            $winter_sun            = ($request->winter_sun == '1') ? (int)$request->winter_sun : 0;

            Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabin_id))
                ->where('_id', new \MongoDB\BSON\ObjectID($winterSeasonId))
                ->update(['winterSeasonYear' => $winterSeasonYear, 'winterSeasonStatus' => $winterSeasonStatus, 'earliest_winter_open' => $earliest_winter_open, 'earliest_winter_close' => $earliest_winter_close, 'latest_winter_open' => $latest_winter_open, 'latest_winter_close' => $latest_winter_close, 'winter_next_season' => $winter_next_season, 'winter_mon' => $winter_mon, 'winter_tue' => $winter_tue, 'winter_wed' => $winter_wed, 'winter_thu' => $winter_thu, 'winter_fri' => $winter_fri, 'winter_sat' => $winter_sat, 'winter_sun' => $winter_sun]);

            echo json_encode(array('successMsgSeasonSave' => __('cabins.successMsgWinterUpt')));
        }
        else {
           echo json_encode(array('errorMsg' => __('cabins.failure')));

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSummer(Request  $request)
    {
        if($request->summerId != '') {
            $updateSeason = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabin_id))
                ->where('_id', new \MongoDB\BSON\ObjectID($request->summerId))
                ->update(['summerSeason' => 0]);
            if($updateSeason > 0) {
                $season = Season::select('_id', 'summerSeason', 'winterSeason')
                    ->where('cabin_id',  new \MongoDB\BSON\ObjectID($request->cabin_id))
                    ->where('_id', new \MongoDB\BSON\ObjectID($request->summerId))
                    ->first();
                if($season->summerSeason === 0 && $season->winterSeason === 0) {
                    Season::where('_id', new \MongoDB\BSON\ObjectID($season->_id))
                        ->delete();
                }
            }
            return response()->json(['summerSeasonDeleteStatus' => 'success' ,'delMsg' => __('cabins.deleteSummerSeasonSuccess')], 201);
        }
        else {
            return response()->json(['summerSeasonDeleteStatus' => 'failed'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWinter(Request  $request)
    {
        if($request->winterId != '') {
            $updateSeason = Season::where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabin_id))
                ->where('_id', new \MongoDB\BSON\ObjectID($request->winterId))
                ->update(['winterSeason' => 0]);
            if($updateSeason > 0) {
                $season   = Season::select('_id', 'summerSeason', 'winterSeason')
                    ->where('cabin_id', new \MongoDB\BSON\ObjectID($request->cabin_id))
                    ->where('_id', new \MongoDB\BSON\ObjectID($request->winterId))
                    ->first();

                if($season->summerSeason === 0 && $season->winterSeason === 0) {
                    Season::where('_id', new \MongoDB\BSON\ObjectID($season->_id))
                        ->delete();
                }
            }

            return response()->json(['winterSeasonDeleteStatus' => 'success', 'delMsg' =>__('cabins.deleteWinterSeasonSuccess')], 201);
        }
        else {
            return response()->json(['winterSeasonDeleteStatus' => 'failed'], 404);
        }
    }
}
