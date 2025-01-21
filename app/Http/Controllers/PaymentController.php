<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function paymentSuccess()
    {
        return view('payment-success');
    }

    public function paymentFailed()
    {
        return view('payment-failed');
    }
}
