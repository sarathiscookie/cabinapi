<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facilities = array(
            'wifi'                                     => __('facilityMessages.Wifi'),
            'shower'                                   => __('facilityMessages.shower available'),
            'food'                                     => __('facilityMessages.Food à la carte'),
            'breakfast'                                => __('facilityMessages.breakfast'),
            'tv'                                       => __('facilityMessages.TV available'),
            'washingMachine'                           => __('facilityMessages.washing machine'),
            'dryingRoom'                               => __('facilityMessages.drying room'),
            'luggageValley'                            => __('facilityMessages.Luggage transport from the valley'),
            'accessibleCar'                            => __('facilityMessages.Accessible by car'),
            'dogsAllowed'                              => __('facilityMessages.dogs allowed'),
            'suitableWheelchairs'                      => __('facilityMessages.Suitable for wheelchairs'),
            'publicAvailable'                          => __('facilityMessages.Public telephone available'),
            'mobileReception'                          => __('facilityMessages.Mobile phone reception'),
            'powerDevices'                             => __('facilityMessages.Power supply for own devices'),
            'wasteBin'                                 => __('facilityMessages.Waste bin'),
            'hutShop'                                  => __('facilityMessages.Hut shop'),
            'advancementIncludingTime'                 => __('facilityMessages.Advancement possibilities including time'),
            'reachableHut'                             => __('facilityMessages.Reachable peaks from hut'),
            'reachablePhone'                           => __('facilityMessages.reachable by phone'),
            'smoking'                                  => __('facilityMessages.Smoking (allowed, forbidden)'),
            'smokeDetector'                            => __('facilityMessages.smoke detector'),
            'CarbonMonoxideDetector'                   => __('facilityMessages.Carbon monoxide detector'),
            'PaymentMethods'                           => __('facilityMessages.Payment methods at the cottage'),
            'HelicopterLandAvailable'                  => __('facilityMessages.Helicopter land available'),
            );
        return response()->json(['facilities' => $facilities], 200);
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
