<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Requests\ContingentRequest;
use App\Http\Controllers\Controller;
use App\Cabin;
use Auth;

class ContingentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabin = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        return view('cabinowner.contingent', ['cabin' => $cabin]);
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
     * @param  \App\Http\Requests\ContingentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ContingentRequest $request)
    {
        $errors = '';

        if($request->notRegularCheckbox === '1') {
            $errors = $this->validate($request, [
                'not_regular_date'             => 'required',
                'not_regular_beds'             => 'required|numeric',
                'not_regular_dorms'            => 'required|numeric',
                'not_regular_emergency_rooms'  => 'numeric|nullable',
                'not_regular_inquiry_guest'    => 'numeric|nullable',
                'not_regular_ms_inquiry_guest' => 'numeric|nullable'
            ]);
        }

        if($request->regularCheckbox === '1') {
            if($request->monday === '1') {
                $errors = $this->validate($request, [
                    'mon_beds'             => 'required|numeric',
                    'mon_dorms'            => 'required|numeric',
                    'mon_emergency_rooms'  => 'numeric|nullable',
                    'mon_inquiry_guest'    => 'numeric|nullable',
                    'mon_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->tuesday === '1') {
                $errors = $this->validate($request, [
                    'tue_beds'             => 'required|numeric',
                    'tue_dorms'            => 'required|numeric',
                    'tue_emergency_rooms'  => 'numeric|nullable',
                    'tue_inquiry_guest'    => 'numeric|nullable',
                    'tue_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->wednesday === '1') {
                $errors = $this->validate($request, [
                    'wed_beds'             => 'required|numeric',
                    'wed_dorms'            => 'required|numeric',
                    'wed_emergency_rooms'  => 'numeric|nullable',
                    'wed_inquiry_guest'    => 'numeric|nullable',
                    'wed_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->thursday === '1') {
                $errors = $this->validate($request, [
                    'thu_beds'             => 'required|numeric',
                    'thu_dorms'            => 'required|numeric',
                    'thu_emergency_rooms'  => 'numeric|nullable',
                    'thu_inquiry_guest'    => 'numeric|nullable',
                    'thu_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->friday === '1') {
                $errors = $this->validate($request, [
                    'fri_beds'             => 'required|numeric',
                    'fri_dorms'            => 'required|numeric',
                    'fri_emergency_rooms'  => 'numeric|nullable',
                    'fri_inquiry_guest'    => 'numeric|nullable',
                    'fri_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->saturday === '1') {
                $errors = $this->validate($request, [
                    'sat_beds'             => 'required|numeric',
                    'sat_dorms'            => 'required|numeric',
                    'sat_emergency_rooms'  => 'numeric|nullable',
                    'sat_inquiry_guest'    => 'numeric|nullable',
                    'sat_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
            if($request->sunday === '1') {
                $errors = $this->validate($request, [
                    'sun_beds'             => 'required|numeric',
                    'sun_dorms'            => 'required|numeric',
                    'sun_emergency_rooms'  => 'numeric|nullable',
                    'sun_inquiry_guest'    => 'numeric|nullable',
                    'sun_ms_inquiry_guest' => 'numeric|nullable'
                ]);
            }
        }
        return redirect()->back()->with('errors', $errors);
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
