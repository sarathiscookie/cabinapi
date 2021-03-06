<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Tour extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'tour';

    /**
     * Indicates if the model should be timestamped. Updating only the usrUpdateDate
     *
     * @var bool
     */
    const CREATED_AT   = 'createdate';
    public $timestamps = [ "CREATED_AT" ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
