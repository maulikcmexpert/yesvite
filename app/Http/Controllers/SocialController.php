<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coin_transactions;
use Laravel\Socialite\Facades\Socialite;
use App\Services\AppleTokenService;
use Illuminate\Support\Facades\Session;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SocialController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider, Request $request)
    {
        // dd($provider);
        if ($request->has('event_login')) {
            session(['event_login' => $request->query('event_login')]);
        }

        if ($provider === 'apple') {
            $clientSecret = app(AppleTokenService::class)->generate();

            config(['services.apple.client_secret' => $clientSecret]);

            return Socialite::driver('apple')->redirect();
        }
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
            if ($provider === 'apple') {
                Log::info('Starting Apple authentication...');

                // Get the code returned by Apple
                $code = request()->get('code');

                // Generate client secret
                $clientSecret = app(AppleTokenService::class)->generate();
                Log::info('Client Secret:', ['client_secret' => $clientSecret]);
                Log::info('Client ID:', ['client_id' => config('services.apple.client_id')]);
                Log::info('Redirect URI:', ['redirect_uri' => config('services.apple.redirect')]);
                Log::info('Apple Token Request Payload:', [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => config('services.apple.redirect'),
                    'client_id' => config('services.apple.client_id'),
                    'client_secret' => $clientSecret,
                ]);

                $response = Http::asForm()->post('https://appleid.apple.com/auth/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => config('services.apple.redirect'),
                    'client_id' => "yesvite.web",
                    'client_secret' => $clientSecret,
                ]);
                Log::info($response);
                if ($response->failed()) {
                    Log::error('Apple Token Exchange Failed: ' . $response->body());

                    return redirect('/login')->with('error', 'Apple sign in failed.');
                }

                $tokenData = $response->json();
                // You now have access_token, id_token etc.
                // Parse id_token to get user info if needed

                // Continue with your user login/registration logic...

            } else {
                $user = Socialite::driver($provider)->user();
            }
        } catch (Exception $e) {
            Log::error('Authentication error: ' . $e->getMessage());

            return redirect('/login')->with('error', 'Authentication failed.');
        }

        $authUser = $this->findOrCreateUser($user, $provider);

        if ($authUser) {
            Auth::login($authUser, true);

            $eventLogin = session('event_login', null);
            session()->forget('event_login');

            return redirect($eventLogin ? '/events' : '/home')->with('msg', 'Logged in successfully!');
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
            if (isset($user->account_status) && $user->account_status != 'Unblock') {
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

            if ($user->account_status == 'Unblock') {
                $user->current_session_id = (isset($session_id) && $session_id != null) ? $session_id : '0';
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

        $users->firstname = (isset($nameParts[0]) && $nameParts[0] != null) ? $nameParts[0] : $socialUser->getName();
        $users->lastname = (isset($nameParts[1]) && $nameParts[1] != null) ? $nameParts[1] : $socialUser->getName();
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
        if (isset($session_id) && $session_id != null) {
            $users->current_session_id = (isset($session_id) && $session_id != null) ? $session_id : '';
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
