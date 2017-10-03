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
use Carbon\Carbon;

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
        /*$privateMessage              = new PrivateMessage;
        $privateMessage->sender_id   = new \MongoDB\BSON\ObjectID('592a81cbd2ae67a4745f42b0');
        $privateMessage->receiver_id = new \MongoDB\BSON\ObjectID($id); //Cabin owner
        $privateMessage->booking_id  = new \MongoDB\BSON\ObjectID('59402b1dd2ae67ed2d43beaa');
        $privateMessage->subject     = 'SWH-17-1000535';
        $privateMessage->text        = 'Message from api';
        $privateMessage->read        = 0;
        $privateMessage->save();*/
        /*remove later end*/

        if($id) {
            $messageUnreads = PrivateMessage::where('receiver_id', new \MongoDB\BSON\ObjectID($id))
                ->where('read', 0)
                ->get();

            $message = '';
            if(count($messageUnreads) > 0) {
                $message .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope-o"></i><span class="label label-success">'.count($messageUnreads).'</span></a><ul class="dropdown-menu list-group"><ul class="products-list product-list-in-box">';
                foreach ($messageUnreads as $messageUnread) {
                    $message .= '<li class="list-group-item"><a href="/cabinowner/inquiry/'.$messageUnread->booking_id.'/'.$messageUnread->sender_id.'" class="product-title">'.$messageUnread->subject.'<span class="label label-info pull-right">'.($messageUnread->created_at)->format("d.m.Y H:i").'</span></a><span class="product-description">'.$messageUnread->text.'</span></li>';
                }
                $message .= '</ul></ul>';

                $redis = Redis::connection();
                $redis->publish('message', $message);
                /*$redis->publish('message', json_encode($message));*/
                return response()->json(['status' => 'success'], 200);
            }
        }
        else {
            return response()->json(['status' => 'No communication'], 404);
        }
    }

    /**
     * Count the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function inquiryAPIUnreadCount($id)
    {
        /*remove later begin*/
        /*$book                = new Booking;
        $book->cabinname     = 'Schwarzwasserhütte';
        $book->user          = new \MongoDB\BSON\ObjectID('592a81cbd2ae67a4745f42b0');
        $book->bookingdate   = Carbon::now();
        $book->invoice_number= 'SWH-16-333336';
        $book->typeofbooking = 1;
        $book->read          = 0;
        $book->status        = '7';
        $book->inquirystatus = 0;
        $book->is_delete     = 0;
        $book->save();*/
        /*remove later end*/

        if($id) {
            $cabin            = Cabin::where('is_delete', 0)
                ->where('cabin_owner', $id)
                ->first();

            $inquiryAPIUnreads = Booking::where('is_delete', 0)
                ->where('cabinname', $cabin->name)
                ->where('typeofbooking', 1)
                ->where('status', "7")
                ->where('read', 0)
                ->get();

            $inquiryCount = '';
            if(count($inquiryAPIUnreads) > 0) {
                $inquiryCount .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i><span class="label label-danger">'.count($inquiryAPIUnreads).'</span></a><ul class="dropdown-menu list-group"><ul class="products-list product-list-in-box">';
                foreach ($inquiryAPIUnreads as $inquiryAPIUnread) {
                    $inquiryCount .= '<li class="list-group-item"><a href="/cabinowner/inquiry/'.$inquiryAPIUnread->_id.'/'.$new = "new".'" class="product-title">'.$inquiryAPIUnread->invoice_number.'<span class="label label-info pull-right">'.($inquiryAPIUnread->bookingdate)->format("d.m.Y H:i").'</span></a><span class="product-description">A new inquiry has registered</span></li>';
                }
                $inquiryCount .= '</ul></ul>';

                $redis = Redis::connection();
                $redis->publish('inquiryCount', $inquiryCount);
                return response()->json(['status' => 'success'], 200);
            }
        }
        else {
            return response()->json(['status' => 'No communication'], 404);
        }
    }
}
