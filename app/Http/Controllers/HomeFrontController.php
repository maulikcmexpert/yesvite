<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifyPendingInvitation;
use App\Models\ServerKey;
use App\Models\User;
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
        $userDetails = User::where('id',  $id)->first();
        if ($userDetails) {
            $userDetails->resend_verification_mail = "1";
            $userDetails->save(); 
        }
        // $userData = [
        //     'username' => $userDetails->firstname,
        //     'email' => $userDetails->email,
        //     'token' => $userDetails->remember_token,
        // ];
        // Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($userDetails) {
        //     $message->to($userDetails->email);
        //     $message->subject('Email Verification Mail');
        // });

        // $title = 'Yesvite-Home';
        // $page = 'front.homefront';
        // return view('layout', compact(
        //     'title',
        //     'page',
        // ));

        return redirect()->route('auth.login')->with("success", value: "Request sent for verification mail !");

    }
}
