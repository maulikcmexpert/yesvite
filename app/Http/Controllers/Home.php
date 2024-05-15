<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        dd(Session::has('user'));
        $webData = Session::get('user');
        dd($webData);
    }
}
