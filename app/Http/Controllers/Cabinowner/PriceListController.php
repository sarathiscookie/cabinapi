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
        $cabin = Cabin::select('price_type','guest_type', 'price')
            ->where('_id', session('cabin_id'))
            ->get();

        foreach($cabin as $one)
        {
            $price_type         = $one->price_type;
            $price              = $one->price;
            $guest_type         = $one->guest_type;
            $count_pricetype    = count($price_type);

        }
        return view('cabinowner.pricelist')->with('price_type', $price_type)
                                                ->with('price', $price)
                                                ->with('guest_type', $guest_type)
                                                ->with('count_pricetype', $count_pricetype);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cabin = Cabin::select('price_type','guest_type', 'price')
            ->where('_id', session('cabin_id'))
            ->get();

        foreach($cabin as $one)
        {
            $price_type         = $one->price_type;
            $price              = $one->price;
            $guest_type         = $one->guest_type;
            $count_pricetype    = count($price_type);

        }
        return view('cabinowner.addprice')->with('price_type', $price_type)
            ->with('price', $price)
            ->with('guest_type', $guest_type)
            ->with('count_pricetype', $count_pricetype);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cabin                 = Cabin::find(session('cabin_id')                                    );
        $cabin->price_type = $request->price_type;
        $cabin->guest_type = $request->guest_type;
        $cabin->price = $request->price;

        $cabin->save();
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
