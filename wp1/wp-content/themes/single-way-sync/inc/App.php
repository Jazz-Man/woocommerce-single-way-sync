<?php

namespace Sync\WpSource;

use Sync\Utils\Gateway\Stripe;
use Sync\Utils\Wc\CheckoutValidation;

class App
{
    public function __construct()
    {
        app_autoload_classes([
			Stripe::class,
			CheckoutValidation::class
        ]);
    }
}
