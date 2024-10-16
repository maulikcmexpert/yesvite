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

        $title = 'Yesvite-Home';
        $page = 'front.homefront';
        return view('layout', compact(
            'title',
            'page',
        ));
    }


    public function ResendVerificationMail(string $id){
        dd(1);

    //     $userDetails = User::where('id',  $id)->first();

    //     $userData = [
    //         // 'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
    //         'username' => $userDetails->firstname,
    //         'email' => $userDetails->email,
    //         'token' => $userDetails->remember_token,
    //     ];
    // Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($userDetails) {
    //         $message->to($userDetails->email);
    //         $message->subject('Email Verification Mail');
    //     });

    //     return redirect()->route('design.index')->with("success", "Email Resend Successfully !");

    }
}
