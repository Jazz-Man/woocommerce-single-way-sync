<?php

namespace Sync\WpSource;

use Sync\Utils\Gateway\Stripe;

class App
{
    public function __construct()
    {
        app_autoload_classes([
			Stripe::class
        ]);
    }
}
