<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CabinLiteRequest;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Cabin;
use App\Userlist;
use App\User;
use App\Country;
use DateTime;
use Response;

class CabinLiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('backend.cabinLite');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request)
    {
        $params  = $request->all();

        $columns = array(
            0 => 'cabinAbbr',
            1 => 'cabinname',
            2 => 'usrEmail',
            3 => 'usrName',
            4 => 'cabinType'
        );

        $totalData      = Cabin::where('is_delete', 0)
                                ->where('other_cabin', '0')->count();

        $totalFiltered  = $totalData;
        $limit          = (int)$request->input('length');
        $start          = (int)$request->input('start');
        $order          = $columns[$params['order'][0]['column']]; //contains column index
        $dir            = $params['order'][0]['dir']; //contains order such as asc/desc

        $q              = Cabin::where('is_delete', 0)
                                ->where('other_cabin', '0');

        if (!empty($request->input('search.value'))) {
            $search     = $request->input('search.value');

            $userSearch = Userlist::where('is_delete', 0)
                ->where('usrlId', 5)
                ->where(function ($query) use ($search) {
                    $query->where('usrEmail', 'like', "%{$search}%")
                        ->orWhere('usrFirstname', 'like', "%{$search}%")
                        ->orWhere('usrLastname', 'like', "%{$search}%");
                })
                ->get();

            if(!empty($userSearch)) {
                foreach ($userSearch as $userData) {
                    $q->where(function($query) use ($userData) {
                        $query->where('cabin_owner', $userData->_id);
                    });

                    $totalFiltered = $q->where(function($query) use ($userData) {
                        $query->where('cabin_owner', $userData->_id);
                    })
                        ->count();
                }
            }
            else {
                $q->where(function ($query) use ($search) {
                    $query->where('invoice_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");

                });
            }
        }

        /* tfoot search functionality for invoice_code begin */
        if (isset($params['columns'][1]['search']['value'])) {
            $q->where(function ($query) use ($params) {
                $query->where('invoice_code', 'like', "%{$params['columns'][1]['search']['value']}%");
            });

            $totalFiltered = $q->where(function ($query) use ($params) {
                $query->where('invoice_code', 'like', "%{$params['columns'][1]['search']['value']}%");
            })
                ->count();
        }

        /* tfoot search functionality for name begin */
        if (isset($params['columns'][2]['search']['value'])) {
            $q->where(function ($query) use ($params) {
                $query->where('name', 'like', "%{$params['columns'][2]['search']['value']}%");
            });

            $totalFiltered = $q->where(function ($query) use ($params) {
                $query->where('name', 'like', "%{$params['columns'][2]['search']['value']}%");
            })
                ->count();
        }


        $cabinLists     = $q->skip($start)
            ->take($limit)
            ->orderBy($order, $dir)
            ->get();

        $data           = [];
        $noData         = '<span class="label label-default">' . __("cabins.noResult") . '</span>';


        if (!empty($cabinLists)) {
            foreach ($cabinLists as $key => $cabinList) {

                /* Condition to check data null or not begin */
                $cabin_name    = ($cabinList->name) ? $cabinList->name : $noData;
                $short_version = ($cabinList->invoice_code) ? $cabinList->invoice_code : $noData;

                $users         = Userlist::select('_id', 'usrFirstname', 'usrLastname', 'usrEmail')
                    ->where('_id', $cabinList->cabin_owner)
                    ->where('is_delete', 0)
                    ->where('usrActive', '1')
                    ->where('usrlId', 5)
                    ->get();

                foreach ($users as $user) {
                    $usrEmail                = $user->usrEmail;
                    $usrFirstname            = ($user->usrFirstname) ? $user->usrFirstname : $noData;
                    $usrLastname             = ($user->usrLastname) ? $user->usrLastname : $noData;
                    $nestedData['cabinAbbr'] = $short_version;
                    $nestedData['cabinname'] = $cabin_name;
                    $nestedData['usrEmail']  = $usrEmail;
                    $nestedData['usrName']   = $usrFirstname . ' ' . $usrLastname;
                    // $nestedData['usrUpdate'] = '<a class="nounderline" href="/admin/cabinlite/edit/' . $cabinList->_id . '"   ><span class="label label-info">'. __('cabins.menuInfo'). '</span> </a><a class="nounderline" href="/admin/cabinlite/contingent/' . $cabinList->_id . '"><span class="label label-default">'. __('cabins.menuContigent'). '</span> </a>
                    //                             <a class="nounderline" href="/admin/cabinlite/seasondetails/' . $cabinList->_id . '"><span class="label label-info">'. __('cabins.menuSeason'). '</span>';
                    $nestedData['usrUpdate'] = '----';
                    $nestedData['cabinType'] = $this->getCabinType($cabinList->booking_type);
                    $data[] = $nestedData;
                }
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
     * Get Cabin Type
     *
     * @param  $id = false if not false return only value based on the key
     * @return \Illuminate\Http\Response
     */
    public function getCabinType($id = false)
    {
        $type = [
            0 => __('cabins.optResev'),
            1 => __('cabins.optBooking'),
            2 => __('cabins.optMail')
        ];
        $result = [];
        if ($id !== false) {
            if (isset($type[$id])) {
                $result = $type[$id];
            }

        } else {
            $result = $type;
        }
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.createCabin', ['cabinOwnerList' => $this->getCabinOwners(), 'cabinType' => $this->getCabinType(), 'countrylist' => $this->getCountry()]);
    }

    /**
     * Show all cabin owner name.
     *
     * @return array
     */
    public function getCabinOwners()
    {
        $user           = [];
        $allCabinOwners = Userlist::select('_id', 'usrFirstname', 'usrLastname', 'usrName', 'company')
            ->where('is_delete', 0)
            ->where('usrlId', 5)
            ->orderBy('usrFirstname')
            ->get();

        foreach ($allCabinOwners as $key => $cabinOwnerList) {
            $cabinOwnerId = $cabinOwnerList->_id;

            $cabin        = Cabin::select('_id', 'name')
                ->where('is_delete', 0)
                ->where('other_cabin', '0')
                ->where('cabin_owner', $cabinOwnerId)
                ->first();

            $user[$cabinOwnerId] = [
                'fname'         => $cabinOwnerList->usrFirstname,
                'lname'         => $cabinOwnerList->usrLastname,
                'company'       => $cabinOwnerList->company,
                'isCabinExists' => $cabin
            ];
        }
        return $user;
    }


    /**
     * Get    cabin owner By Id .
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function getCabinOwnerById($id)
    {
        $users = Userlist::select('usrName', 'usrFirstname', 'usrLastname', 'usrEmail', 'usrAddress', 'usrTelephone', 'usrMobile', 'usrZip', 'usrCity', 'company')
            ->where('_id', $id)
            ->get();
        return $users();
    }
    /**
     * Show all Country
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountry()
    {

        $country = Country::select('_id', 'name')
            /*->where('is_delete', 0)*/
            ->orderBy('name')->get();

        return $country;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CabinLiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CabinLiteRequest $request)
    {
        $cabin                          = new Cabin;
        $cabin->name                    = $request->cabin_name;
        $cabin->invoice_code            = $request->cabin_code;
        $cabin->height                  = $request->height;
        $cabin->reachable               = $request->availability;
        $cabin->club                    = $request->club;
        $cabin->checkin_from            = $request->check_in;
        $cabin->reservation_to          = $request->check_out;
        $cabin->region                  = $request->region;
        $cabin->website                 = $request->website;
        $cabin->prepayment_amount       = (float)$request->deposit;
        $cabin->halfboard               = ($request->halfboard) ? $request->halfboard : "0";
        $cabin->halfboard_price         = $request->halfboard_price;
        $cabin->cabin_owner             = $request->cabin_owner;
        $cabin->country                 = $request->country;
        $cabin->booking_type            = $request->booking_type;
        $cabin->reservation_cancel      = $request->cancel;
        $cabin->tours                   = $request->tours;
        $cabin->interior                = $request->facility;
        $cabin->payment_type            = $request->payment;
        $cabin->latitude                = $request->latitude;
        $cabin->longitude               = $request->longitude;
        $cabin->other_details           = $request->details;
        $cabin->zip                     = $request->zip;
        $cabin->place                   = $request->city;
        $cabin->street                  = $request->street;
        $cabin->fax                     = $request->fax;
        $cabin->vat                     = $request->vat;
        $cabin->legal                   = $request->legal;
        $cabin->sleeping_place          = (int)$request->sleeping_place;
        $cabin->other_cabin             = "0";
        $cabin->invoice_autonum         = 100000;
        $cabin->is_delete               = 0;
        $cabin->created_at              = date('Y-m-d H:i:s');

        /* if other_neighbour_cabin is not empty appending with  neighbour_cabin array*/
        $cabin->neighbour_cabin         = $this->saveNeighbourCabin($request);
        $cabin->save();

        return redirect(url('/admin/cabinlite'))->with('successMsgSave', __('cabins.successMsgSave'));
    }

    /**
     * for saving as Neighbour cabin
     *
     * @param  string $request
     * @return \Illuminate\Http\Response
     */
    public function saveNeighbourCabin($request)
    {
        $insertId = [];
        if (!empty($request->other_neighbour_cabin)) {
            $neighbours = $this->neighbourCabinFilter($request->other_neighbour_cabin);
            foreach ($neighbours as $key => $value) {
                if ($value['name'] != "" && $value['url'] != "") {
                    $neighbour_cabin                = new Cabin;
                    $neighbour_cabin->name          = $value['name'];
                    $neighbour_cabin->website       = $value['url'];
                    $neighbour_cabin->other_cabin   = '1';
                    $neighbour_cabin->is_delete     = 0;
                    $neighbour_cabin->save();
                    $insertId[]                     = $neighbour_cabin->getKey();
                }
            }

            /* if other_neighbour_cabin is not empty appending with neighbour_cabin array*/
            $neighbourId        = (!empty($request->neighbour)) ? $request->neighbour : [];
            $merge_array        = array_merge($neighbourId, $insertId);
            $neighbour_cabin    = $merge_array;
        }
        else {
            $neighbour_cabin    = $request->neighbour;
        }
        return $neighbour_cabin;
    }

    /**
     * for Filter  neighbourCabin
     *
     * @param  $neighbours
     * @return \Illuminate\Http\Response
     */
    public function neighbourCabinFilter($neighbours)
    {
        $out = [];
        foreach ($neighbours as $key => $value) {
            foreach ($value as $k => $val) {
                $out[$k][$key] = $val;
            }
        }
        return $out;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $cabin =

        Cabin::select('_id', 'cabin_owner')->where('is_delete', 0)
            ->where('_id', $id)
            ->first();

        $userDetails =

        Userlist::select('usrFirstname', 'usrLastname', 'usrEmail', 'usrZip', 'usrCity', 'usrAddress', 'usrCountry', 'usrTelephone', 'usrMobile','company')
                ->where('is_delete', 0)
                ->where('_id', $cabin->cabin_owner)
                ->first();

        //dd(Userlist::get());

        $get_cabin = Cabin::where('_id', $id)->first();

        return view('backend.editCabin', [
            'cabinOwnerList' => $this->getCabinOwners(),
            'cabinType'      => $this->getCabinType(),
            'cabin'          => $get_cabin,
            'countrylist'    => $this->getCountry(),
            'userDetails'    => $userDetails
        ]);
    }
    /**
     *  update the specified resource.
     *
     * @param    $request
     * @return \Illuminate\Http\Response
     */

    public function updateContactinfo(CabinLiteRequest $request)
    {
        if (isset($request->formPart) && $request->formPart == 'updateContactInfo') {
            $userDetails = Userlist::findOrFail($request->cabin_owner);
            $userDetails->usrFirstname = $request->firstname;
            $userDetails->usrLastname = $request->lastname;
            $userDetails->usrTelephone = $request->telephone;
            $userDetails->usrCountry = $request->usrCountry;
            $userDetails->usrMobile = $request->mobile;
            $userDetails->usrAddress = $request->usrAddress;
            $userDetails->usrCity = $request->usrCity;
            $userDetails->usrZip = $request->usrZip;
            $userDetails->save();
            echo json_encode(array('successMsg' => __('cabins.contactSuccessMsgUdt')));
        } else {
            echo json_encode(array('errorMsg' => __('cabins.failure')));
        }

    }
    /**
     *  update the specified resource.
     *
     * @param    $request
     * @return \Illuminate\Http\Response
     */
    public function updateBillingInfo(CabinLiteRequest $request)
    {

        if(isset($request->formPart) && $request->formPart == 'updateBillingInfo') {
            $upt_array = [
                'place' => $request->city,
                'street' => $request->street,
                'zip' => $request->zip,
                'fax' => $request->fax,
                'vat' => $request->vat,
                'legal' => $request->legal,
            ];
            // 'company' => $request->company for user table
            Cabin::where('_id', $request->cabin_id)->update($upt_array);
            /* Company is updated from user table*/
            $userDetails = Userlist::findOrFail($request->cabin_owner);
            $userDetails->company = $request->company;
            $userDetails->save();
            /* Company is updated from user table*/

            echo  json_encode(array('successMsg' => __('cabins.billingSuccessMsgUdt')));
        }
        else {
            echo json_encode(array('errorMsg' => __('cabins.failure')));
        }

    }



    /**
     *  update the specified resource.
     *
     * @param    $request
     * @return \Illuminate\Http\Response
     */

    public function updateCabinInfo(CabinLiteRequest $request)
    {


        if (isset($request->formPart) && $request->formPart == 'updateCabin' && $request->cabin_id != "") {
            /* Mongo UTCDateTime begin */
            $date_now = date("Y-m-d H:i:s");
            $orig_date = new DateTime($date_now);
            $orig_date = $orig_date->getTimestamp();
            $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date * 1000);
            /* Mongo UTCDateTime end */
            $upt_array = [
                'invoice_code' => $request->cabin_code,
                'cabin_owner' => $request->cabin_owner,
                'height' => $request->height,
                'club' => $request->club,
                'reservation_cancel' => $request->cancel,
                'reachable' => $request->availability,
                'tours' => $request->tours,
                'checkin_from' => $request->check_in,
                'reservation_to' => $request->check_out,
                'interior' => $request->facility,
                'halfboard' => $request->halfboard,
                'halfboard_price' => $request->halfboard_price,
                'payment_type' => $request->payment,
                'prepayment_amount' => (float)$request->deposit,
                'country' => $request->country,
                'website' => $request->website,
                'booking_type' => $request->booking_type,
                'sleeping_place'=>(int)$request->sleeping_place,
                'other_details' => $request->details,
                'region' => $request->region,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'neighbour_cabin' => $this->saveNeighbourCabin($request),
                'updated_at' => $utcdatetime];

            Cabin::where('_id', $request->cabin_id)->update($upt_array);

            echo json_encode(array('successMsg' => __('cabins.successMsgUdt')));

        } else {
            echo json_encode(array('errorMsg' => __('cabins.failure')));
        }

    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CabinLiteRequest $request)
    {

//
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}