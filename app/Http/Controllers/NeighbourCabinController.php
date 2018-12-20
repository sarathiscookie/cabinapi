<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabin;

class NeighbourCabinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.neighbourCabin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params         = $request->all();

        $totalData      = Cabin::where('is_delete', 0)
            ->where('other_cabin', '1')->count();

        $totalFiltered  = $totalData;
        $limit          = (int)$request->input('length');
        $start          = (int)$request->input('start');

        $q              = Cabin::where('is_delete', 0)
            ->where('other_cabin', '1');

        if (!empty($request->input('search.value'))) {
            $search     = $request->input('search.value');

            $q->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });

            $totalFiltered = $q->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
                ->count();
        }

        $cabinLists     = $q->skip($start)
            ->take($limit)
            ->get();

        $data           = [];
        $noData         = '<span class="label label-default">' . __("cabins.noResult") . '</span>';


        if (!empty($cabinLists)) {
            foreach ($cabinLists as $key => $cabinList) {
                $nestedData['cabinName']           = ($cabinList->name) ? $cabinList->name : $noData;;
                $nestedData['switchToNormalCabin']   = '<button class="btn btn-primary btn-sm switchToNormalCabin" value="'.$cabinList->_id.'">'. __('neighbourCabin.switchToNormalButton'). '</button>';
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            'draw' => (int)$params['draw'],
            'recordsTotal' => (int)$totalData,
            'recordsFiltered' => (int)$totalFiltered,
            'data' => $data
        );

        return response()->json($json_data);

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
     * Update the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function cabinTypeChangeToNormal(Request $request)
    {
        $cabin      = Cabin::where('is_delete', 0)
            ->where('other_cabin', '1')
            ->find($request->cabinId);

        if($cabin) {
            $cabin->other_cabin = '0';
            $cabin->save();

            return response()->json(['successMsg' => __('cabins.successMsgUdt')]);
        }
        else {
            return response()->json(['successMsg' => __('cabins.failure')]);
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
