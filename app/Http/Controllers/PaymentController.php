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
    public function showCheckout()
    {
        $prices = [
            '15' => 'price_1QjcEZEunmtSe18EsKcUG55D',
            '30' => 'price_1QjcKZEunmtSe18EC967NkmQ',
            '50' => 'price_1QjcKZEunmtSe18EDqd3hc04',
            '100' => 'price_1QjcKZEunmtSe18EWVn3qgKG',
            '100' => 'price_1QjcKZEunmtSe18EWVn3qgKG',
            '200' => 'price_1QjcKZEunmtSe18EKDO6nALE',
            '500' => 'price_1QjcKZEunmtSe18EjIk3cpDe',
            '750' => 'price_1QjcKZEunmtSe18El3iZy6nK',
            '1000' => 'price_1QjcKZEunmtSe18EAzOBdf4p',
        ];

        return view('checkout', ['prices' => $prices, 'stripePublicKey' => config('services.stripe.public')]);
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
                'success_url' => route('payment.success'),
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

            // Fetch the payment success details from the request (Stripe webhook or URL params)
            $input = $request->all(); // Assume data is passed via POST request (or adjust accordingly)

            // Basic validation (you can customize based on what you expect)
            if (!isset($input['orderId']) || !isset($input['coins']) || !isset($input['productId']) || !isset($input['packageName'])) {
                return redirect()->route('checkout')->withErrors(['error' => 'Required data missing']);
            }

            // Prepare subscription data
            $app_id = "TokenPurchased";
            $product_id = "STRIPE";
            $startDate = Carbon::now();

            // Create new subscription
            $new_subscription = new UserSubscription();
            $new_subscription->user_id = $user->id;
            $new_subscription->orderId = $input['orderId'];
            $new_subscription->packageName = $input['packageName'];
            $new_subscription->countryCode = $input['regionCode'] ?? ''; // Default empty if not available
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
                $coin_transaction->status = '0'; // Assuming status '0' means successful
                $coin_transaction->type = 'credit'; // Type 'credit' since coins are being added
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
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            // \Log::error('Payment success error: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->route('checkout')->withErrors(['error' => 'An error occurred during payment processing']);
        }
    }

    public function paymentFailed()
    {
        return view('payment-failed');
    }
}
