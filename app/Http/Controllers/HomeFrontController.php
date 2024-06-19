<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifyPendingInvitation;
use App\Models\ServerKey;
use Illuminate\Support\Facades\Mail;

class HomeFrontController extends Controller
{
    public function index()
    {
        $serverKey  = ServerKey::first();
        $SERVER_API_KEY = $serverKey->firebase_key;
        dd($SERVER_API_KEY);
        $title = 'Home';
        $page = 'front.homefront';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
}
