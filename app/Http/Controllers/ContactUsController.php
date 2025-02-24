<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ContactMail;

class ContactUsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Contact Us';
        $page = 'front.contact_us';
        //   $js = ['contact'];
        return view('layout', compact(
            'title',
            'page',
        ));
    }
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        
        Mail::to(env('MAIL_USERNAME'))->send(new ContactMail($data));
        // Mail::to('hepin.k.cmexpertise@gmail.com')->send(new ContactMail($data));
        return back()->with('msg', 'Your message has been sent successfully!');
    }
}
