<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testmongo;

class TestmongoController extends Controller
{
    public function index()
    {
        $test = Testmongo::all();
        dd($test);
    }
}
