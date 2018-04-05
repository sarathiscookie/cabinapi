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

}
