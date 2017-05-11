<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Testmongo extends Eloquent
{
    protected $collection = 'restaurants';
}
