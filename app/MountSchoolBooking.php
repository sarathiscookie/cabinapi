<?php

namespace App;

/*use Illuminate\Database\Eloquent\Model;*/
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Cabin;
use DateTime;
use App\Traits\DateGenerate;
use App\Traits\DateFormat;

class MountSchoolBooking extends Eloquent
{
    use DateGenerate, DateFormat;

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
     * Redirect back with error message if validations fail
     *
     * @param {StoreBookingRequest | UpdateBookingRequest} $request
     * @return {void}
     */
    public function handleRequest($request, $cabin)
    {
        // Save booking data if it has a check in date
        if ($request->check_in1[0] != NULL) {
            $this->check_in = DateTime::createFromFormat('d.m.y', $request->check_in1[0])->format('Y-m-d');
        }

        // Save booking data if it has a check out date
        if ($request->check_out1[0] != NULL) {
            $this->reserve_to = DateTime::createFromFormat('d.m.y', $request->check_out1[0])->format('Y-m-d');
        }

        // Save booking data if it has a tour name
        if ($request->has('tour_name')) {
            $this->tour_name = $request->tour_name;
        }

        // Save booking data if it has a tour number
        if ($request->has('tour_number')) {
            $this->ind_tour_no = $request->tour_number;
        }

        // Save booking data if it has a cabin name
        if ($request->has('cabin_name')) {
            $this->cabin_name = $request->cabin_name;
        }

        // Save booking data if it has beds number
        if ($request->has('beds')) {
            $this->beds = (string) $request->beds;
        }

        // Save booking data if it has sleeps number
        if ($request->has('sleeps')) {
            $this->sleeps = (string) $request->sleeps;
        }

        // Save booking data if it has dormitories number
        if ($request->has('dorms')) {
            $this->dormitory = (string) $request->dorms;
        }

        // Save booking data if it has halfboard option
        if ($request->has('halfboard')) {
            $this->halfboard = 1;
        } else {
            $this->halfboard = 0;
        }

        // Save the records in the database
        $this->save();
    }

    /**
     * Process the requst of canceling a booking.
     *
     * @return {void}
     */
    public function handleCancelRequest()
    {
        // Cancel the booking records
        $this->status = "2";
        $this->save();
    }
}
