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
        // dd(createShortUrl("https://yesvite.com"));

        // $users = UserOpt::select('id', 'event_id', 'event_invited_user_id')
        //     ->groupBy('event_id')
        //     ->get();
        // dd($users);
        // handleIncomingMessage("+918780258675", "yes");
        // handleSMSInvite("+91 97238 40340", "yesvite web", "srryghhhggguvj", 814, 2787);
        return [
            '15' => ['priceId' => 'price_1QjcEZEunmtSe18EsKcUG55D', 'coins' => 15, 'price' => 21.00],

            '30' => ['priceId' => 'price_1QnbS0EunmtSe18EFuz4qYpN', 'coins' => 30, 'price' => 36.99],

            '50' => ['priceId' => 'price_1QnbS0EunmtSe18EGEbp4K0L', 'coins' => 50, 'price' => 45.00],

            '100' => ['priceId' => 'price_1QnbR6EunmtSe18EjI3fQkjt', 'coins' => 100, 'price' => 70.00],

            '200' => ['priceId' => 'price_1QnbPnEunmtSe18EWVSpsUKw', 'coins' => 250, 'price' => 100.00],

            '500' => ['priceId' => 'price_1QnbSfEunmtSe18E4DQoalB7', 'coins' => 500, 'price' => 125.99],
        ];
    }
}
