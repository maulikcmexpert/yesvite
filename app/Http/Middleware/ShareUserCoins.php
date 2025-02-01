<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ShareUserCoins
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        $coins = $user ? $user->coins : 0;

        View::share('coins', $coins);
        View::share('UserId', $user->id);

        return $next($request);
    }
}
