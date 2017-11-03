<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsRequest;
use App\Cabin;
use App\Region;
use App\Userlist;
use Auth;
use DateTime;

class DetailsController extends Controller
{
    /**
     * Array for interior.
     *
     * @param  string  $interior
     * @return \Illuminate\Http\Response
     */
    public function interiorLabel($interior = null)
    {
        $facilities = array(
            'Wifi'                                      => __("details.interiorWifi"),
            'shower available'                          => __("details.interiorShower"),
            'Food à la carte'                           => __("details.interiorMealCard"),
            'breakfast'                                 => __("details.interiorBreakfast"),
            'TV available'                              => __("details.interiorTv"),
            'washing machine'                           => __("details.interiorWashingMachine"),
            'drying room'                               => __("details.interiorDryingRoom"),
            'Luggage transport from the valley'         => __("details.interiorLuggageTransport"),
            'Accessible by car'                         => __("details.interiorAccessCar"),
            'dogs allowed'                              => __("details.interiorDogsAllowed"),
            'Suitable for wheelchairs'                  => __("details.interiorWheelchairs"),
            'Public telephone available'                => __("details.interiorPublicPhone"),
            'Mobile phone reception'                    => __("details.interiorPhoneReception"),
            'Power supply for own devices'              => __("details.interiorPowerSupply"),
            'Waste bin'                                 => __("details.interiorDustbins"),
            'Hut shop'                                  => __("details.interiorCabinShop"),
            'Advancement possibilities including time'  => __("details.interiorAscentPossibility"),
            'reachable by phone'                        => __("details.interiorAccessibleTelephone"),
            'Smoking (allowed, forbidden)'              => __("details.interiorSmokingAllowed"),
            'smoke detector'                            => __("details.interiorSmokeDetector"),
            'Carbon monoxide detector'                  => __("details.interiorCarbMonoDetector"),
            'Helicopter land available'                 => __("details.interiorHelicopterLand"),
        );

        if($interior != null) {
            if(array_key_exists($interior, $facilities)) {
                return $facilities[$interior];
            }
        }
        else {
            return $facilities;
        }
    }

    /**
     * Array for reservation cancel.
     *
     * @return \Illuminate\Http\Response
     */
    public function reservationCancel()
    {
        $array = array(
            '0'  => __("details.selectReservationCancelType"),
            '1'  => __("details.cancelDeadlineBegin").' 1 '.__("details.cancelDeadlineEnd"),
            '2'  => __("details.cancelDeadlineBegin").' 2 '.__("details.cancelDeadlineEnd"),
            '3'  => __("details.cancelDeadlineBegin").' 3 '.__("details.cancelDeadlineEnd"),
            '4'  => __("details.cancelDeadlineBegin").' 4 '.__("details.cancelDeadlineEnd"),
            '5'  => __("details.cancelDeadlineBegin").' 5 '. __("details.cancelDeadlineEnd"),
            '6'  => __("details.cancelDeadlineBegin").' 6 '. __("details.cancelDeadlineEnd"),
            '7'  => __("details.cancelDeadlineBegin").' 7 '. __("details.cancelDeadlineEnd"),
            '8'  => __("details.cancelDeadlineBegin").' 8 '. __("details.cancelDeadlineEnd"),
            '9'  => __("details.cancelDeadlineBegin").' 9 '. __("details.cancelDeadlineEnd"),
            '10' => __("details.cancelDeadlineBegin").' 10 '. __("details.cancelDeadlineEnd"),
            '14' => __("details.cancelDeadlineBegin").' 14 '. __("details.cancelDeadlineEnd"),
            '15' => __("details.cancelDeadlineBegin").' 15 '. __("details.cancelDeadlineEnd"),
            '20' => __("details.cancelDeadlineBegin").' 20 '. __("details.cancelDeadlineEnd"),
            '30' => __("details.cancelDeadlineBegin").' 30 '. __("details.cancelDeadlineEnd"),
            '60' => __("details.cancelDeadlineBegin").' 60 '. __("details.cancelDeadlineEnd"),
            '90' => __("details.cancelDeadlineBegin").' 90 '. __("details.cancelDeadlineEnd"),
            '180' => __("details.cancelDeadlineBegin").' 180 '. __("details.cancelDeadlineEnd"),
            '365' => __("details.cancelDeadlineBegin").' 365 '. __("details.cancelDeadlineEnd"),
        );

        return $array;
    }

    /**
     * Array for payment type.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentType()
    {
        $array = array(
            '0' => __("details.cabinBoxLabelPayTypeCash"),
            '1' => __("details.cabinBoxLabelPayTypeDebit"),
            '2' => __("details.cabinBoxLabelPayTypeCredit"),
        );

        return $array;
    }

    /**
     * Array for cabin.
     *
     * @return \Illuminate\Http\Response
     */
    public function cabins()
    {
        $neighbour = '';

        $cabins     = Cabin::select('_id', 'name')
            ->where('is_delete', 0)
            ->get();

        if(count($cabins) > 0) {
            $neighbour = $cabins;
        }

        return $neighbour;
    }

    /**
     * Getting all regions.
     *
     * @return \Illuminate\Http\Response
     */
    public function regions()
    {
        $regions = '';

        $regionsQuery = Region::select('name')
            ->where('is_delete', 0)
            ->get();

        if(count($regionsQuery) > 0) {
            $regions  = $regionsQuery;
        }

        return $regions;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userDetails = Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrZip', 'usrCity', 'usrAddress', 'usrCountry', 'usrTelephone', 'usrMobile', 'company')
            ->where('is_delete', 0)
            ->where('_id',  new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();

        $cabin       = Cabin::where('is_delete', 0)
            ->where('name', session('cabin_name'))
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        return view('cabinowner.details', ['userDetails' => $userDetails, 'cabin' => $cabin]);
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
            ->where('_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->first();

        if(count($userDetails) > 0) {
            return view('cabinowner.detailsContactUpdate', ['userDetails' => $userDetails]);
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editBillingIfo()
    {
        $userDetails     = '';
        $cabin           = Cabin::select('name', 'zip', 'street', 'place', 'tax', 'legal', 'telephone', 'vat', 'fax')
            ->where('is_delete', 0)
            ->where('name', session('cabin_name'))
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            $userDetails = Userlist::select('company')
                ->where('is_delete', 0)
                ->where('_id',  new \MongoDB\BSON\ObjectID(Auth::user()->_id))
                ->first();
            return view('cabinowner.detailsBillingUpdate', ['cabin' => $cabin, 'userCompany' => $userDetails]);
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCabinIfo()
    {
        $cabin           = Cabin::where('is_delete', 0)
            ->where('name', session('cabin_name'))
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            return view('cabinowner.detailsCabinUpdate', ['cabin' => $cabin]);
        }
        return redirect()->back();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DetailsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateContactInfo(DetailsRequest $request)
    {
        if(isset($request->updateContact)) {
            $userDetails                = Userlist::findOrFail(Auth::user()->_id);
            $userDetails->usrFirstname  = $request->firstname;
            $userDetails->usrLastname   = $request->lastname;
            $userDetails->usrTelephone  = $request->telephone;
            $userDetails->usrCountry    = $request->country;
            $userDetails->usrMobile     = $request->mobile;
            $userDetails->usrAddress    = $request->street;
            $userDetails->usrCity       = $request->city;
            $userDetails->usrZip        = $request->zip;
            $userDetails->save();

            $request->session()->flash('successContact', __('details.contactSuccessMsg'));
            return redirect(url('cabinowner/details'));
        }
        else {
            return redirect()->back()->with('failure', __('details.failure'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DetailsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateBillingInfo(DetailsRequest $request)
    {
        if(isset($request->updateBilling)) {
            Cabin::where('is_delete', 0)
                ->where('name', session('cabin_name'))
                ->where('cabin_owner', Auth::user()->_id)
                ->update(['legal' => $request->legal, 'tax' => $request->tax, 'telephone' => $request->telephone, 'zip' => $request->zip, 'place' => $request->city, 'street' => $request->street, 'fax' => $request->fax, 'vat' =>$request->vat]);

            $userDetails          = Userlist::findOrFail(Auth::user()->_id);
            $userDetails->company = $request->company;
            $userDetails->save();

            $request->session()->flash('successBilling', __('details.billingSuccessMsg'));
            return redirect(url('cabinowner/details'));
        }
        else {
            return redirect()->back()->with('failure', __('details.failure'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DetailsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCabinIfo(DetailsRequest $request)
    {
        if(isset($request->updateCabin)) {

            /* Mongo UTCDateTime begin */
            $date_now    = date("Y-m-d H:i:s");
            $orig_date   = new DateTime($date_now);
            $orig_date   = $orig_date->getTimestamp();
            $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);
            /* Mongo UTCDateTime end */

            Cabin::where('is_delete', 0)
                ->where('name', session('cabin_name'))
                ->where('cabin_owner', Auth::user()->_id)
                ->update(['name' => $request->cabinname, 'height' => $request->height, 'club' => $request->club, 'reservation_cancel' => $request->cancel, 'reachable' => $request->availability, 'tours' => $request->tours, 'checkin_from' => $request->checkin, 'reservation_to' => $request->checkout, 'interior' => $request->facility, 'halfboard' => $request->halfboard, 'halfboard_price' => $request->price, 'payment_type' => $request->payment, 'neighbour_cabin' => $request->neighbour, 'prepayment_amount' => $request->deposit, 'website' => $request->website, 'other_details' => $request->details, 'region' => $request->region, 'latitude' => $request->latitude, 'longitude' => $request->longitude, 'updated_at' => $utcdatetime]);

            $request->session()->flash('successCabin', __('details.cabinSuccessMsg'));
            return redirect(url('cabinowner/details'));
        }
        else {
            return redirect()->back()->with('failure', __('details.failure'));
        }
    }


    /**
     * Get the specified resource.
     *
     * @param  int  $neighbour_cabin
     * @return \Illuminate\Http\Response
     */
    public function neighbourCabin($neighbour_cabin)
    {
        $cabinName = '';

        $cabin = Cabin::select('name')
            ->where('is_delete', 0)
            ->where('_id', new \MongoDB\BSON\ObjectID($neighbour_cabin))
            ->first();

        if(count($cabin) > 0) {
            $cabinName = $cabin->name;
        }

        return $cabinName;
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
