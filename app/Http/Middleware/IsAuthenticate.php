<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        
        if (!Auth::guard('web')->user()) {
            return $next($request);
        }
        $secondUser = Auth::guard('web')->user();
        if ($secondUser->email_verified_at == NULL) {
            return redirect()->route('auth.login')->with('msg', 'Please check and verify your email address.');
            // return Redirect::to(URL::to('home'));
        
        }
    }
}
