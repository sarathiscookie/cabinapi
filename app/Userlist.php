<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Userlist extends Eloquent
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'user';

    /**
     * Indicates if the model should be timestamped. Updating only the usrUpdateDate
     *
     * @var bool
     */
    const UPDATED_AT   = 'usrUpdateDate';
    public $timestamps = [ "UPDATED_AT" ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
