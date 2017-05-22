<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegionsRequest;
use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::where('is_delete', 0)
            ->paginate(15);

        return response()->json(['regions' => $regions], 200);
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
     * @param  \App\Http\Requests\RegionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionsRequest $request)
    {
        $region            = new Region;

        $region->name      = $request->name;

        $region->landname  = $request->landname;

        $region->is_delete = 0;

        $region->save();

        return response()->json(['message' => 'Created new region successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RegionsRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegionsRequest $request, $id)
    {
        $region            = Region::findOrFail($id);
        $region->name      = $request->name;
        $region->landname  = $request->landname;
        $region->save();

        return response()->json(['message' => 'Updated region successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $region            = Region::findOrFail($id);
        $region->is_delete = 1;
        $region->save();

        return response()->json(['message' => 'Region deleted'], 201);
    }
}
