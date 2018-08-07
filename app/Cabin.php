<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Cabin extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'cabins';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * Set the cabin code as upper case.
     *
     * @param  string  $value
     * @return void
     */
    public function setinvoice_codeAttribute($value)
    {
        $this->attributes['invoice_code'] = strtoupper($value);
    }
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

    protected $dates = ['created_at', 'updated_at', 'deleted_on'];

}
