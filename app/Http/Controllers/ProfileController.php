<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
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
