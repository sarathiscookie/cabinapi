<?php

namespace App;

/*use Illuminate\Database\Eloquent\Model;*/
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DateTime;

class MountSchoolBooking extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'mschool';

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

    protected $dates = ['bookingdate', 'check_in', 'reserve_to'];

    /**
     * Process the request data and save it to the database
     *
     * @param {StoreBookingRequest | UpdateBookingRequest} $request
     * @return {void}
     */
    public function handleRequest($request)
    {
        if ($request->check_in1[0] != NULL) {
            $this->check_in = DateTime::createFromFormat('d.m.y', $request->check_in1[0])->format('Y-m-d');
        }

        if ($request->check_out1[0] != NULL) {
            $this->reserve_to = DateTime::createFromFormat('d.m.y', $request->check_out1[0])->format('Y-m-d');
        }

        if ($request->has('tour_name')) {
            $this->tour_name = $request->tour_name;
        }

        if ($request->has('tour_number')) {
            $this->ind_tour_no = $request->tour_number;
        }

        if ($request->has('cabin_name')) {
            $this->cabin_name = $request->cabin_name;
        }

        if ($request->has('beds')) {
            $this->beds = (string) $request->beds;
        }

        if ($request->has('sleeps')) {
            $this->sleeps = (string) $request->sleeps;
        }

        if ($request->has('dorms')) {
            $this->dormitory = (string) $request->dorms;
        }

        if ($request->has('halfboard')) {
            $this->halfboard = "1";
        }

        // Save the records in the database
        $this->save();
    }

    /**
     * Process the requst of removal for a booking.
     *
     * @return {void}
     */
    public function handleDestroyRequest()
    {
        // Remove the booking records
        $this->delete();
    }
}
