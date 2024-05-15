<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Cookie;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = 'auth/login';
        $title = "Login";
        $js = ['login'];

        return view('auth/main', compact('page', 'title', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter a valid Email',
            'password.required' => 'Please enter a Password',
            'password.min' => 'Password must be at least 8 characters',
        ]);


        $remember = $request->has('remember'); // Check if "Remember Me" checkbox is checked

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            $sessionArray = ['id' => encrypt($user->id), 'username' => $user->firstname . ' ' . $user->lastname];
            Session::put(['user' => $sessionArray]);
            if (Session::has('user')) {

                if ($remember != null) {
                    Cookie::queue('email', $user->email, 120);
                    Cookie::queue('password', $user->password, 120);
                } else {

                    Cookie::forget('email');
                    Cookie::forget('password');
                }
                return Redirect::to(URL::to('/home'))->with('success', 'Logged in successfully!');;
            } else {
                return  Redirect::to('/')->with('error', 'Invalid credentials!');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
