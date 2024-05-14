<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        if (Session::exists('key')) {
            // Session key exists, retrieve its value
            $value = Session::get('key');
            dd($value);
        } else {
            // Session key does not exist
        }
    }
}
