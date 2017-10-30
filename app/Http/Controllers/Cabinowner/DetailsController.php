<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsRequest;
use App\Cabin;
use App\Userlist;
use Auth;

class DetailsController extends Controller
{
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

        $cabin       = Cabin::select('name', 'zip', 'street', 'place', 'tax', 'legal', 'telephone', 'vat', 'fax')
            ->where('is_delete', 0)
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
            return redirect(url('cabinowner/details'))->with('successContact', __('details.contactSuccessMsg'));
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

            return redirect(url('cabinowner/details'))->with('successBilling', __('details.billingSuccessMsg'));
        }
        else {
            return redirect()->back()->with('failure', __('details.failure'));
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
