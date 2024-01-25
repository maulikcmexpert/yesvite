<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::channel('api')->debug('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'input' => $request->all(),
        ]);

        $response = $next($request);

        // Log the response
        Log::channel('api')->debug('API Response', [
            'status_code' => $response->status(),
            'response' => $response->getContent(),
        ]);

        return $response;
    }
}
