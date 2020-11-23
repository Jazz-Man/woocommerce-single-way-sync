<?php

namespace Sync\WpDestination;

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
