<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
// use DB;
use App\Models\UserOpt;

class BaseController extends Controller
{
    public function __construct()
    {
        // Share prices with all views globally
        // dd($this->getPrices());
        View::share('prices', $this->getPrices());
        // Share coins globally with all views
        $user = $this->getUser();
        $coins = $user ? $user->coins : 0;

        View::share('coins', $coins);
    }
    protected function getUser()
    {
        return Auth::guard('web')->user();
    }
    protected function getPrices()
    {
        // dd(config('app.debug', true));
        // dd(createShortUrl("https://yesvite.com"));

        // $users = UserOpt::select('id', 'event_id', 'event_invited_user_id')
        //     ->groupBy('event_id')
        //     ->get();
        // dd($users);
        // handleIncomingMessage("+918780258675", "yes");
        // handleSMSInvite("+91 97238 40340", "yesvite web", "srryghhhggguvj", 814, 2787);
        return [
            '15' => ['priceId' => 'price_1QrC0dEunmtSe18EOUZgSmuy', 'coins' => 15, 'price' => 14.99],

            '30' => ['priceId' => 'price_1QrC6BEunmtSe18EmbwOERSM', 'coins' => 30, 'price' => 24.99],

            '50' => ['priceId' => 'price_1QrC6BEunmtSe18ErhNVPfNH', 'coins' => 50, 'price' => 34.99],

            '100' => ['priceId' => 'price_1QrC6BEunmtSe18E07TR4iCC', 'coins' => 100, 'price' => 59.99],

            '200' => ['priceId' => 'price_1QrC6BEunmtSe18EQU01LFNR', 'coins' => 250, 'price' => 99.99],

            '500' => ['priceId' => 'price_1QrC6BEunmtSe18EnzZELzAp', 'coins' => 500, 'price' => 125.99],
        ];
    }
}
