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

class PaymentController extends Controller
{
    protected function getPrices()
    {
        return [
            '15' => ['priceId' => 'price_1QjcEZEunmtSe18EsKcUG55D', 'coins' => 15],
            '30' => ['priceId' => 'price_1QjcKZEunmtSe18EC967NkmQ', 'coins' => 30],
            '50' => ['priceId' => 'price_1QjcKZEunmtSe18EDqd3hc04', 'coins' => 50],
            '100' => ['priceId' => 'price_1QjcKZEunmtSe18EWVn3qgKG', 'coins' => 100],
            '200' => ['priceId' => 'price_1QjcKZEunmtSe18EKDO6nALE', 'coins' => 200],
            '500' => ['priceId' => 'price_1QjcKZEunmtSe18EjIk3cpDe', 'coins' => 500],
            '750' => ['priceId' => 'price_1QjcKZEunmtSe18El3iZy6nK', 'coins' => 750],
            '1000' => ['priceId' => 'price_1QjcKZEunmtSe18EAzOBdf4p', 'coins' => 1000],
        ];
    }

    // Method to show the checkout page
    public function showCheckout()
    {
        $prices = $this->getPrices();
        return view('checkout', ['prices' => $prices, 'stripePublicKey' => config('services.stripe.public')]);
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

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'priceId' => 'required|string',
        ]);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $validated['priceId'],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?paid_id={CHECKOUT_SESSION_ID}', // Include session ID in success URL
                'cancel_url' => route('payment.failed'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        try {
            // Get the current authenticated user
            $user = Auth::guard('web')->user();

            if (!$user) {
                return redirect()->route('login')->withErrors(['error' => 'User not authenticated']);
            }

            // Get the session ID from the query parameter
            $sessionId = $request->query('paid_id');
            if (!$sessionId) {
                return redirect()->route('checkout')->withErrors(['error' => 'Session ID is missing']);
            }
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            $priceId = $session->line_items->data[0]->price->id;

            $coins = $this->getCoinsForPriceId($priceId); // Get coins based on priceId

            if (!$coins) {
                return redirect()->route('checkout')->withErrors(['error' => 'Invalid price ID']);
            }
            // Fetch the session details from Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status == 'paid') {
                // Payment successful, proceed with updating subscription and user balance

                $input = [
                    'orderId' => $session->id,
                    'coins' => 100,  // Example: You may pass dynamic data from the session or product
                    'productId' => 'STRIPE',
                    'packageName' => 'Standard Package',
                    'purchaseToken' => $session->payment_intent,  // Payment Intent ID as the purchase token
                    'regionCode' => $session->customer_address->country ?? '',  // Example: Extract region info
                ];

                // Prepare subscription data
                $startDate = Carbon::now();
                $new_subscription = new UserSubscription();
                $new_subscription->user_id = $user->id;
                $new_subscription->orderId = $input['orderId'];
                $new_subscription->packageName = $input['packageName'];
                $new_subscription->countryCode = $input['regionCode'];
                $new_subscription->startDate = $startDate;
                $new_subscription->productId = $input['productId'];
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

                    // Return success view
                    return view('payment-success', ['user' => $user, 'coins' => $total_coin]);
                } else {
                    return redirect()->route('checkout')->withErrors(['error' => 'Failed to create subscription']);
                }
            } else {
                return redirect()->route('checkout')->withErrors(['error' => 'Payment failed']);
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            //\Log::error('Payment success error: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->route('checkout')->withErrors(['error' => 'An error occurred during payment processing']);
        }
    }

    public function paymentFailed()
    {
        return view('payment-failed');
    }
}
