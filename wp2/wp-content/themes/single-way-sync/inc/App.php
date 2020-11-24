<?php

namespace Sync\WpDestination;

use Sync\Utils\Gateway\Stripe;
use Sync\Utils\Wc\CheckoutValidation;
use Sync\WpDestination\RestAPI\RestApi;

class App
{
	public function __construct()
	{
		app_autoload_classes([
			Stripe::class,
			CheckoutValidation::class,
			RestApi::class
		]);
	}

}
