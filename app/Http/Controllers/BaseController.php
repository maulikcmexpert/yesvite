<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        // Share prices with all views globally
        View::share('prices', $this->getPrices());
    }

    protected function getPrices()
    {
        return [
            '15' => ['priceId' => 'price_1QjcEZEunmtSe18EsKcUG55D', 'coins' => 15, 'price' => 22.50],
            '30' => ['priceId' => 'price_1QjcKZEunmtSe18EC967NkmQ', 'coins' => 30, 'price' => 40.50],
            '50' => ['priceId' => 'price_1QjcKZEunmtSe18EDqd3hc04', 'coins' => 50, 'price' => 60.00],
            '100' => ['priceId' => 'price_1QjcKZEunmtSe18EWVn3qgKG', 'coins' => 100, 'price' => 110.00],
            '200' => ['priceId' => 'price_1QjcKZEunmtSe18EKDO6nALE', 'coins' => 200, 'price' => 200.00],
            '500' => ['priceId' => 'price_1QjcKZEunmtSe18EjIk3cpDe', 'coins' => 500, 'price' => 450.00],
            '750' => ['priceId' => 'price_1QjcKZEunmtSe18El3iZy6nK', 'coins' => 750, 'price' => 600.00],
            '1000' => ['priceId' => 'price_1QjcKZEunmtSe18EAzOBdf4p', 'coins' => 1000, 'price' => 750.00],
        ];
    }
}
