<?php

namespace Sync\Utils\Wc;

use JazzMan\AutoloadInterface\AutoloadInterface;

class CheckoutValidation implements AutoloadInterface
{
    public function load()
    {
        add_filter('woocommerce_checkout_fields', [$this, 'remove_billing_postcode']);
        add_filter('woocommerce_default_address_fields', [$this, 'optional_postcode']);
    }

    /**
     * @param array $fields
     * @return array
     */
    public function remove_billing_postcode($fields)
    {
        if (! empty($fields['billing']) && ! empty($fields['billing']['billing_postcode'])) {
            unset($fields['billing']['billing_postcode']);
        }

        return $fields;
    }

    /**
     * Make Zip/Postcode field optional
     * @param array $fields
     * @return array
     */
    public function optional_postcode($fields)
    {
        if (! empty($fields['postcode'])) {
            $fields['postcode']['required'] = false;
        }

        return $fields;
    }
}
