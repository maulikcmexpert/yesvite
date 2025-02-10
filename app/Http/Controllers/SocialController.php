<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coin_transactions;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;


class SocialController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {


        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            
            $user = Socialite::driver($provider)->user();
            
        } catch (Exception $e) {
            return redirect('/login');
        }

        // Check if the user already exists
        $authUser = $this->findOrCreateUser($user, $provider);
        // dd($user);
        if($authUser){
            Auth::login($authUser, true);
            return redirect()->intended('/profile')->with('msg', 'Logged in successfully!');
        }
     

    }

    /**
     * Find or create a user.
     *
     * @param  \Laravel\Socialite\Contracts\User  $user
     * @param string $provider
     * @return \App\Models\User
     */
    public function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();
        Session::start();
        Session::regenerate();
        $session_id = Session::getId();
        if ($user) {
            if(isset($user->account_status) && $user->account_status != 'Unblock'){
                return redirect('/login')->withErrors([
                    'email' => 'Ban User: Temporarily or permanently suspend user.',
                ]);
            }
            if ($provider == 'google') {
                $user->gmail_token_id = $socialUser->getId();
            } elseif ($provider == 'facebook') {
                $user->facebook_token_id = $socialUser->getId();
            } elseif ($provider == 'instagram') {
                $user->instagram_token_id = $socialUser->getId();
            } elseif ($provider == 'apple') {
                $user->apple_token_id = $socialUser->getId();
            }
            
            if($user->account_status == 'Unblock'){
                $user->current_session_id = (isset($session_id) && $session_id != null)?$session_id:'0';
                $sessionArray = [
                    'id' => encrypt($user->id),
                    'first_name' => $user->firstname,
                    'last_name' => $user->lastname,
                    'username' => $user->firstname . ' ' . $user->lastname,
                    'profile' => ($user->profile != NULL || $user->profile != "") ? asset('storage/profile/' . $user->profile) : ""
                ];
                Session::put(['user' => $sessionArray]);
            }
            $user->save();
            return  $user;
        }
        $nameParts = explode(' ', $socialUser->getName());
        $users =  new User();
        $randomString = Str::random(30);

        $users->firstname = (isset($nameParts[0]) && $nameParts[0] != null)?$nameParts[0]:$socialUser->getName();
        $users->lastname = (isset($nameParts[1]) && $nameParts[1] != null)?$nameParts[1]:$socialUser->getName();
        $users->email = $socialUser->getEmail();
        $users->gmail_token_id = $socialUser->getId();
        $users->facebook_token_id = $socialUser->getId();
        $users->instagram_token_id = $socialUser->getId();
        $users->apple_token_id = $socialUser->getId();
        $users->remember_token =   $randomString;
        $users->coins =  env('DEFAULT_COIN');

        // $users->email_verified_at = strtotime(date('Y-m-d  h:i:s'));;
        $users->email_verified_at = strtotime(date('Y-m-d  h:i:s'));
        $users->account_status = 'Unblock';
        if(isset($session_id) && $session_id != null){
            $users->current_session_id = (isset($session_id) && $session_id != null)?$session_id:'';
        }
        $users->register_type = 'web social signup';
        $users->save();

        $newUser = User::where('id', $users->id)->first();

        $coin_transaction = new Coin_transactions();
        $coin_transaction->user_id = $users->id;
        $coin_transaction->status = '0';
        $coin_transaction->type = 'credit';
        $coin_transaction->coins = env('DEFAULT_COIN');
        $coin_transaction->current_balance = env('DEFAULT_COIN');
        $coin_transaction->description = 'Signup Bonus';
        $coin_transaction->endDate = Carbon::now()->addYear()->toDateString();
        $coin_transaction->save();

        $sessionArray = [
            'id' => encrypt($newUser->id),
            'first_name' => $newUser->firstname,
            'last_name' => $newUser->lastname,
            'username' => $newUser->firstname . ' ' . $newUser->lastname,
            'profile' => ($newUser->profile != NULL || $newUser->profile != "") ? asset('storage/profile/' . $newUser->profile) : asset('public/storage/profile/no_profile.png')
        ];
        Session::put(['user' => $sessionArray]);


        if (Session::has('user')) {

            if (isset($remember) && $remember != null) {
                Cookie::queue('email', $newUser->email, 120);
                Cookie::queue('password', $newUser->password, 120);
            } else {

                Cookie::forget('email');
                Cookie::forget('password');
            }
            return $newUser;
        }
    }
}
