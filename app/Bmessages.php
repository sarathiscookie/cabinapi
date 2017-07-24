<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Bmessages extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'bmessages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Indicates if the model should be timestamped. Updating only the usrUpdateDate
     *
     * @var bool
     */
    const CREATED_AT   = 'message_date';
    public $timestamps = [ "CREATED_AT" ];

}
