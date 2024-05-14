<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        $webData = Session::get('web');
        dd($webData);
    }
}
