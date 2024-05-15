<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class IsAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Session::has('user')) {
            return Redirect::to(URL::to('home'));
        }
        return Redirect::to(URL::to('/'))->with('error', 'Unauthorised');
    }
}
