<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabin;
use App\Booking;
use Auth;
use Redis;
use App\MountSchoolBooking;
use App\PrivateMessage;
use App\Events\MessageEvent;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cabinowner.bookings');
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

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookingCount()
    {
        $totalData = '';
        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin) {
                $cabin_name = $cabin->name;
                $totalData  = Booking::where('is_delete', 0)
                    ->where('cabinname', $cabin_name)
                    ->where('status', '!=', '7')
                    ->count();
            }
            return $totalData;
        }
        return $totalData;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mSchoolBookingCount()
    {
        $totalData = '';
        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin) {
                $cabin_name = $cabin->name;
                $totalData  = MountSchoolBooking::where('is_delete', 0)
                    ->where('cabin_name', $cabin_name)
                    ->count();
            }
            return $totalData;
        }
        return $totalData;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inquiryListCount()
    {
        $totalData = '';
        $cabins = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->get();

        if(count($cabins) > 0) {
            foreach ($cabins as $cabin) {
                $cabin_name = $cabin->name;
                $totalData  = Booking::where('is_delete', 0)
                    ->where('cabinname', $cabin_name)
                    ->where('typeofbooking', 1)
                    ->where('status', "7")
                    ->count();
            }
            return $totalData;
        }
        return $totalData;
    }

    /**
     * Collecting cabin name.
     *
     * @return \Illuminate\Http\Response
     */
    public function cabinName()
    {
        $cabin_name = '';

        $cabin = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            $cabin_name = $cabin->name;
        }

        return $cabin_name;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privateMessageCount()
    {
        $count = '';

        $count = PrivateMessage::where('receiver_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->where('read', 0)
            ->count();
        if ($count > 0){
            return $count;
        }

        return $count;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privateMessageList()
    {
        $messageList = [];

        $messages = PrivateMessage::where('receiver_id', new \MongoDB\BSON\ObjectID(Auth::user()->_id))
            ->where('read', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($messages) > 0){
            foreach ($messages as $message){
                $messageList[] = $message;
            }
        }

        return $messageList;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inquiryUnreadCount()
    {
        $count = '';

        $cabin = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            $count = Booking::where('is_delete', 0)
                ->where('cabinname', $cabin->name)
                ->where('typeofbooking', 1)
                ->where('status', "7")
                ->where('read', 0)
                ->count();
        }
        return $count;
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inquiryUnreadLists()
    {
        $inquiryUnreadLists = [];

        $cabin = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            $unreadLists = Booking::where('is_delete', 0)
                ->where('cabinname', $cabin->name)
                ->where('typeofbooking', 1)
                ->where('status', "7")
                ->where('read', 0)
                ->orderBy('bookingdate', 'desc')
                ->get();

            if (count($unreadLists) > 0) {
                foreach ($unreadLists as $unreadList) {
                    $inquiryUnreadLists[] = $unreadList;
                }
            }
        }

        return $inquiryUnreadLists;
    }

    /**
     * Count the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function privateMessageAPICount($id)
    {
        /*remove later begin*/
        $privateMessage              = new PrivateMessage;
        $privateMessage->sender_id   = new \MongoDB\BSON\ObjectID('592a81cbd2ae67a4745f42b0');
        $privateMessage->receiver_id = new \MongoDB\BSON\ObjectID($id);
        $privateMessage->booking_id  = new \MongoDB\BSON\ObjectID('59402b1dd2ae67ed2d43beaa');
        $privateMessage->subject     = 'SWH-17-1000535';
        $privateMessage->text        = 'Message from api';
        $privateMessage->read        = 0;
        $privateMessage->save();
        /*remove later end*/


        $count = PrivateMessage::where('receiver_id', new \MongoDB\BSON\ObjectID($id))
            ->where('read', 0)
            ->count();

        $redis = Redis::connection();
        $redis->publish('message', $count);

        return response()->json(['status' => $count], 201);
    }

    /**
     * Count the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function inquiryAPIUnreadCount()
    {
        $count = '';

        $cabin = Cabin::where('is_delete', 0)
            ->where('cabin_owner', Auth::user()->_id)
            ->first();

        if(count($cabin) > 0) {
            $count = Booking::where('is_delete', 0)
                ->where('cabinname', $cabin->name)
                ->where('typeofbooking', 1)
                ->where('status', "7")
                ->where('read', 0)
                ->count();

            event(new MessageEvent($count));
        }
        return $count;
    }*/
}
