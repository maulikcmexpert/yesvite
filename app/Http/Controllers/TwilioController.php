<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserOpt;

class TwilioController extends Controller
{
    public function handleIncomingMessage(Request $request)
    {
        sendSMSForApplication("+919723840340", 'Yesvite:Pratik has invited you to Test. Reply "YES" to view details, RSVP, and to receive future invites. Reply STOP to opt out.');
        die;
        // Get the message details from Twilio's webhook
        $from = $request->input('From'); // Sender's phone number
        $body = $request->input('Body'); // Message content (case insensitive)

        // Sanitize and process the message
        $body = strtolower(trim($body));

        handleIncomingMessage($from, $body);

        // $user = UserOpt::where('phone', $from)->first();

        if ($body == 'yes') {
            // // Opt-in logic
            // if ($user) {
            //     $user->opt_in_status = true;
            //     $user->save();
            // } else {
            //     // Create a new user if not found
            //     UserOpt::create([
            //         'phone' => $from,
            //         'opt_in_status' => true,
            //     ]);
            // }

            return response("You've been subscribed. Thank you!", 200);
        } elseif ($body == 'stop') {
            // // Opt-out logic
            // if ($user) {
            //     $user->opt_in_status = false;
            //     $user->save();
            // }

            return response("You've been unsubscribed. Reply START to resubscribe.", 200);
        } else {
            // Handle unknown messages
            return response("Invalid response. Reply YES to subscribe or STOP to unsubscribe.", 200);
        }
    }
}
