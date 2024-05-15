<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {

        $title = 'Profile';
        $page = 'front.profile';
        return view('layout', compact(
            'title',
            'page',

        ));
    }
}
