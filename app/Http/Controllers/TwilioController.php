<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserOpt;
use Illuminate\Support\Facades\Log;

class TwilioController extends Controller
{
    public function handleIncomingMessage(Request $request)
    {
        Log::info('Webhook triggered: Incoming message data', [
            'request_payload' => $request->all()
        ]);
        $from = $request->input('From');
        $body = $request->input('Body');
        Log::info('Extracted data from webhook', [
            'from' => $from,
            'body' => $body
        ]);

        $body = strtolower(trim($body));
        Log::info('Normalized body for processing', ['normalized_body' => $body]);

        handleIncomingMessage($from, $body);



        if ($body == 'yes') {
            Log::info('User subscribed', ['from' => $from]);

            return response("You've been subscribed. Thank you!", 200);
        } elseif ($body == 'stop') {
            Log::info('User unsubscribed', ['from' => $from]);

            return response("You've been unsubscribed. Reply START to resubscribe.", 200);
        } else {
            Log::info('Invalid response received', ['from' => $from, 'body' => $body]);

            return response("Invalid response. Reply YES to subscribe or STOP to unsubscribe.", 200);
        }
    }
}
