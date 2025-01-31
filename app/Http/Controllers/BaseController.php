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

        // $users = UserOpt::select('id', 'event_id', 'event_invited_user_id')
        //     ->groupBy('event_id')
        //     ->get();
        // dd($users);
        // handleIncomingMessage("+918200120722", "yes");
        // handleSMSInvite("+91 97238 40340", "yesvite web", "srryghhhggguvj", 814, 2787);
        return [
            '15' => ['priceId' => 'price_1QjcEZEunmtSe18EsKcUG55D', 'coins' => 15, 'price' => 21.00],
            '30' => ['priceId' => 'price_1QjcKZEunmtSe18EC967NkmQ', 'coins' => 30, 'price' => 36.99],
            '50' => ['priceId' => 'price_1QjcKZEunmtSe18EDqd3hc04', 'coins' => 50, 'price' => 45.00],
            '100' => ['priceId' => 'price_1QjcKZEunmtSe18EWVn3qgKG', 'coins' => 100, 'price' => 70.00],
            '200' => ['priceId' => 'price_1QjcKZEunmtSe18EKDO6nALE', 'coins' => 250, 'price' => 100.00],
            '500' => ['priceId' => 'price_1QjcKZEunmtSe18EjIk3cpDe', 'coins' => 500, 'price' => 125.99],
        ];
    }
}
