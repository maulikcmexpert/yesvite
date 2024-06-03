<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifyPendingInvitation;
use Illuminate\Support\Facades\Mail;

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
