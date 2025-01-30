<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use DB;

class UrlController extends Controller
{
    public function createShortUrl($longUrl)
    {
        try {
            do {
                // Generate a random 15-character key
                $shortUrlKey = Str::random(10);
            } while (Url::where('short_url_key', $shortUrlKey)->exists()); // Ensure uniqueness

            // Insert into the database
            $url = Url::create([
                'long_url' => $longUrl,
                'short_url_key' => $shortUrlKey,
                'expires_at' => now()->addDays(90) // Expire after 90 days
            ]);

            return "https://yesvite.com/rsvp/{$shortUrlKey}";
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleShortUrl($shortUrlKey)
    {
        dd($shortUrlKey);
        DB::enableQueryLog();
        // Look up the short URL in the database
        $url = Url::where('short_url_key', $shortUrlKey)
            ->where('expires_at', '>', now()) // Ensure it's not expired
            ->first();

        if ($url) {
            return redirect($url->long_url); // Redirect to the long URL
        }
        dd(DB::getQueryLog()); // Dump and die with query log

        return response()->json(['error' => 'URL not found or expired'], 404);
    }
}
