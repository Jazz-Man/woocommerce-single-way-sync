<?php

namespace Sync\Utils\Gateway;

use JazzMan\AutoloadInterface\AutoloadInterface;

class Stripe implements AutoloadInterface
{
    public function load()
    {
        add_filter('option_woocommerce_stripe_settings', [$this, 'freeze_options']);
    }

    /**
     * @param mixed $settings
     * @return mixed
     */
    public function freeze_options($settings)
    {
        $settings['testmode'] = 'yes';
        $settings['test_publishable_key'] = 'pk_test_51HhGNOCL5HkBbCMCtccaOuP8CYPD1CaH9W0HVyXzhSi7ZPkx3zTcs94BovPRANwIEscivoWh595Qa8JmECndfM9r000WebAN0Z';
        $settings['test_secret_key'] = 'sk_test_51HhGNOCL5HkBbCMCMSSELpDgOiBB0V2zoDrvrQr4os1KyvcAJkh3eJoEmDtHjCiCCNa5uT6xxVdE68mO0Zjra1UX00hWhwD1wU';

        return $settings;
    }
}
