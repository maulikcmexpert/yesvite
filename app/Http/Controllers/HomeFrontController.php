<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeFrontController extends Controller
{
    public function index()
    {
        $title = 'Home';
        $page = 'front.homefront';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
}
