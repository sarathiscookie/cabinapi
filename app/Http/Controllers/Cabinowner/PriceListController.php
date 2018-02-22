<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabin;
use Auth;
class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabin = Cabin::select('price_type', 'guest_type', 'price')
            ->where('is_delete', 0)
            ->find(session('cabin_id'));

        return view('cabinowner.pricelist', ['cabin' => $cabin]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cabin = Cabin::select('price_type', 'guest_type', 'price')
            ->where('is_delete', 0)
            ->find(session('cabin_id'));

        return view('cabinowner.addprice', ['cabin' => $cabin]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->price) && !empty($request->price_type) && !empty($request->guest_type)) {
            $cabin             = Cabin::find(session('cabin_id'));
            $cabin->price_type = $request->price_type;
            $cabin->guest_type = $request->guest_type;
            $cabin->price      = array_map('floatval', $request->price); //Convert all values of an array to floats
            $cabin->save();
            $request->session()->flash('success', __('pricelist.success'));
        }
        else {
            $request->session()->flash('failure', __('pricelist.failure'));
        }
        return redirect('cabinowner/pricelist');
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

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
