<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifyPendingInvitation;
use App\Models\ServerKey;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\TextData;
use App\Models\EventDesignCategory;
use App\Models\EventDesignSubCategory;
class HomeFrontController extends BaseController
{
    public function index()
    {
        $serverKey  = ServerKey::first();

        $title = 'Yesvite-Home';
        $page = 'front.homefront';

        $images = TextData::all();
        $categories = EventDesignCategory::with(['subcategory.textdatas'])->get();
        $getDesignData =  EventDesignCategory::with('subcategory')->get();
        $getDesignData = EventDesignCategory::all();
        $getsubcatData = EventDesignSubCategory::all();
        return view('layout', compact(
            'title',
            'page',
            'images',
            'getDesignData',
            'categories'
        ));
    }


    public function ResendVerificationMail(string $id)
    {
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

    public function triggerQueueWork()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('app.url') . '/run-queue-work',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                // 'Authorization: Bearer your_token_if_needed'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['error' => $err]);
        } else {
            return response()->json(['response' => $response]);
        }
    }
    public function viewAllImages()
    {

        $images = TextData::all();

        return view('front.homefront', compact('images'));
    }

    public function homeDesign(){
        $serverKey  = ServerKey::first();

        $title = 'Yesvite-Home';
        $page = 'front.home_design';

        $images = TextData::all();
        $categories = EventDesignCategory::with(['subcategory.textdatas'])->get();
        $getDesignData =  EventDesignCategory::with('subcategory')->get();
        $getDesignData = EventDesignCategory::all();
        $getsubcatData = EventDesignSubCategory::all();
        return view('layout', compact(
            'title',
            'page',
            'images',
            'getDesignData',
            'categories'
        ));
    }


}
