<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;

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

        Auth::login($authUser, true);
        dd(Auth::login($authUser, true));
        return redirect()->intended('/home');
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
        if ($user) {
            if ($provider == 'google') {

                $user->gmail_token_id = $socialUser->getId();
            } elseif ($provider == 'facebook') {
                $user->facebook_token_id = $socialUser->getId();
            } elseif ($provider == 'instagram') {
                $user->instagram_token_id = $socialUser->getId();
            } elseif ($provider == 'apple') {
                $user->apple_token_id = $socialUser->getId();
            }
            $user->save();

            return  $user;
        }

        $users =  new User();
        $users->firstname = $socialUser->getName();
        $users->email = $socialUser->getEmail();
        $users->gmail_token_id = $socialUser->getId();
        $users->facebook_token_id = $socialUser->getId();
        $users->instagram_token_id = $socialUser->getId();
        $users->apple_token_id = $socialUser->getId();
        $users->save();

        $newUser = User::where('id', $users->id)->first();
        $sessionArray = [
            'id' => encrypt($newUser->id),
            'username' => $newUser->firstname . ' ' . $newUser->lastname,
            'profile' => ($newUser->profile != NULL || $newUser->profile != "") ? asset('public/storage/profile/' . $newUser->profile) : asset('public/storage/profile/no_profile.png')
        ];
        Session::put(['user' => $sessionArray]);


        if (Session::has('user')) {

            if ($remember != null) {
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
