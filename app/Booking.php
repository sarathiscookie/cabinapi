<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Booking extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'booking';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Eloquent allows you to work with Carbon/DateTime objects instead of MongoDate objects.
     * Internally, these dates will be converted to MongoDate objects when saved to the database.
     */

    protected $dates = ['bookingdate', 'checkin_from', 'reserve_to'];

    /**
     * Append each order to booking
     */
    protected $appends = ['order_number'];

    public function getOrderNumberAttribute()
    {
        return Order::where('_id', $this->order_id)
            ->pluck('order_id')
            ->first();
    }
}
