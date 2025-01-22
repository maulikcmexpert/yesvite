<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Coin_transactions,
    User,
    UserSubscription,
};

class PaymentController extends BaseController
{


    // Method to show the checkout page
    public function showCheckout()
    {
        $user = $this->getUser();
        dd($user);
        return view('checkout', ['stripePublicKey' => config('services.stripe.public')]);
    }

    // Method to get coins for a given priceId
    protected function getCoinsForPriceId($priceId)
    {
        // Iterate through the prices to find the corresponding coins
        foreach ($this->getPrices() as $price) {
            if ($price['priceId'] == $priceId) {
                return $price['coins'];
            }
        }
        return null; // Return null if no matching priceId is found
    }

    public function processPayment($priceId)
    {
        // $validated = $request->validate([
        //     'priceId' => 'required|string',
        // ]);
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not authenticated']);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?paid_id={CHECKOUT_SESSION_ID}', // Include session ID in success URL
                'cancel_url' => route('payment.failed'),
                'expand' => ['line_items'],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function checkPayment(Request $request)
    {
        $validated = $request->validate([
            'priceId' => 'required|string',
        ]);
        $user = Auth::guard('web')->user();
        $sessionKey = 'payment_session_' . $user->id . '_' . $validated['priceId'];
        // Check if the session already exists
        if (session()->has($sessionKey)) {
            $sessionData = session($sessionKey);

            if ($sessionData['status'] !== 'ideal') {
                session()->forget($sessionKey);
            }
            return response()->json([
                'status' => $sessionData['status'],
                'message' => 'Session already exists',
                'data' => $user->coins,
            ]);
        }
        $startDate = Carbon::now();
        $new_subscription = new UserSubscription();
        $new_subscription->user_id = $user->id;
        $new_subscription->device_type = 'WEB';
        $new_subscription->startDate = $startDate;
        $new_subscription->productId = $validated['priceId'];
        $new_subscription->type = 'product';
        $new_subscription->save();
        // Create a new session with initial status 'idle'
        session()->put($sessionKey, [
            'user_id' => $user->id,
            'price_id' => $validated['priceId'],
            'status' => 'idle',
            'created_at' => $startDate,
        ]);

        return response()->json([
            'status' => 'idle',
            'message' => 'New session created',
            'data' => $user->coins,
        ]);
    }
    public function paymentSuccess(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not authenticated']);
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            // Get the session ID from the query parameter
            $sessionId = $request->query('paid_id');
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            if (!$sessionId) {
                echo "invalid1";
                die;
                //return redirect()->route('checkout')->withErrors(['error' => 'Session ID is missing']);
            }
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $lineItems = $stripe->checkout->sessions->allLineItems($sessionId, []);



            if (!empty($lineItems->data)) {
                $priceId = $lineItems->data[0]->price->id; // Get the price ID
                $coins = $this->getCoinsForPriceId($priceId); // Map price ID to credits (coins)

            }

            if (!$coins) {
                echo $coins;
                die;
                //return redirect()->route('checkout')->withErrors(['error' => 'Invalid price ID']);
            }


            // Fetch the session details from Stripe

            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status == 'paid') {
                // Payment successful, proceed with updating subscription and user balance

                $input = [
                    'orderId' => $session->id,
                    'coins' => $coins,
                    'productId' => $priceId,
                    'packageName' => 'Standard Package',
                    'purchaseToken' => $session->payment_intent,  // Payment Intent ID as the purchase token
                    'regionCode' => $session->customer_address->country ?? '',  // Example: Extract region info
                ];

                // Prepare subscription data
                $startDate = Carbon::now();
                $sessionKey = 'payment_session_' . $user->id . '_' . $priceId;
                if (session()->has($sessionKey)) {
                    $sessionData = session($sessionKey);

                    // Retrieve the most recent subscription if available
                    $new_subscription = UserSubscription::where([
                        'user_id' => $user->id,
                        'device_type' => 'WEB',
                        'productId' => $priceId,
                        'startDate' => $sessionData['created_at']
                    ])
                        ->orderBy('id', 'DESC')
                        ->first();

                    // If no subscription found for the given details, create a new one
                    if (!$new_subscription) {
                        $new_subscription = new UserSubscription();
                        $new_subscription->user_id = $user->id; // Add user ID if it's not already set
                        $new_subscription->device_type = 'web'; // Device type should be 'web' for this case
                        $new_subscription->productId = $priceId; // Add priceId as product ID
                        $new_subscription->startDate = $startDate; // Set start date
                    }
                } else {
                    // If the session does not exist, create a new subscription
                    $new_subscription = new UserSubscription();
                    $new_subscription->user_id = $user->id; // Ensure user ID is added
                    $new_subscription->device_type = 'web'; // Set device type
                    $new_subscription->productId = $priceId; // Set the priceId as product ID
                    $new_subscription->startDate = $startDate; // Set the start date
                }

                $new_subscription->price = $coins;
                $new_subscription->orderId = $input['orderId'];
                $new_subscription->packageName = $input['packageName'];
                $new_subscription->countryCode = $input['regionCode'];

                $new_subscription->type = 'product';
                $new_subscription->purchaseToken = $input['purchaseToken'];

                // Save subscription if everything is correct
                if ($new_subscription->save()) {
                    // Add credits to user coins
                    $total_coin = $user->coins + $input['coins'];
                    User::where('id', $user->id)->update(['coins' => $total_coin]);

                    // Create a coin transaction record
                    $coin_transaction = new Coin_transactions();
                    $coin_transaction->user_id = $user->id;
                    $coin_transaction->user_subscription_id = $new_subscription->id;
                    $coin_transaction->status = '0';
                    $coin_transaction->type = 'credit';
                    $coin_transaction->coins = $input['coins'];
                    $coin_transaction->current_balance = $total_coin;
                    $coin_transaction->description = $input['coins'] . ' Credits Bulk Credits';
                    $coin_transaction->endDate = Carbon::now()->addYears(5)->toDateString(); // Expiration date
                    $coin_transaction->save();

                    if (session()->has($sessionKey)) {
                        session()->put($sessionKey . '.status', 'success');
                    }
                    return view('payment-success', ['user' => $user, 'coins' => $total_coin]);
                } else {
                    $sessionKey = 'payment_session_' . $user->id . '_' . $priceId;
                    if (session()->has($sessionKey)) {
                        session()->put($sessionKey . '.status', 'failed');
                    }
                    return redirect()->route('checkout')->withErrors(['error' => 'Failed to create subscription']);
                }
            } else {
                $sessionKey = 'payment_session_' . $user->id . '_' . $priceId;
                if (session()->has($sessionKey)) {
                    session()->put($sessionKey . '.status', 'failed');
                }
                return redirect()->route('checkout')->withErrors(['error' => 'Payment failed']);
            }
        } catch (\Exception $e) {
            $allSessions = session()->all();

            $userPaymentSessions = collect($allSessions)
                ->filter(function ($value, $key) use ($user) {
                    return str_starts_with($key, 'payment_session_' . $user->id);
                });
            foreach ($userPaymentSessions as $key => $session) {
                // Update session status to false
                session()->put($key, array_merge($session, ['status' => 'failed']));
            }
            dd($e);
            // Log the error for debugging purposes
            //\Log::error('Payment success error: ' . $e->getMessage());

            // Redirect with an error message
            //return redirect()->route('checkout')->withErrors(['error' => 'An error occurred during payment processing']);
        }
    }

    public function paymentFailed()
    {
        return view('payment-failed');
    }
}
