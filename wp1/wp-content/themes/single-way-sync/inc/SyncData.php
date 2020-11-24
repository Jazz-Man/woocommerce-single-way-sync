<?php

namespace Sync\WpSource;

use JazzMan\AutoloadInterface\AutoloadInterface;

class SyncData implements AutoloadInterface
{
    /**
     * @var string
     */
    private $cron_event = 'order_sync';

    private $consumer_key = 'ck_542df9afd6ba3994679f161a6aa12d84010c805e';

    private $consumer_secret = 'cs_4a2e709b249eaa9c7aed2260e0c5d1cf038393d4';

    private $sync_endpoint = 'http://159.65.76.231/wp2/index.php/wp-json/sync/order';

    public function load()
    {
        add_action('woocommerce_payment_complete', [$this, 'add_order_sync_cron_job']);

        // just for debugging
//        add_action('init', [$this, 'add_order_sync_cron_job']);

        add_action($this->cron_event, [$this, 'order_sync']);
    }

    /**
     * Perhaps it will be the ID of a non-existent order.
     * This ID is passed here only for testing
     *
     * @param int $order_id
     */
    public function add_order_sync_cron_job($order_id)
    {
        if (! wp_next_scheduled($this->cron_event)) {
            wp_schedule_single_event(\time(), $this->cron_event, \compact('order_id'));
        }
    }

    /**
     * @param int $order_id
     */
    public function order_sync($order_id)
    {
        try {
            $order = wc_get_order($order_id);

            if ($order instanceof \WC_Abstract_Order && $order->has_status('completed')) {
                $customer = new \WC_Customer($order->get_customer_id());

                $args = [
                    'timeout' => 45,
                    'blocking' => true,
                    'httpversion' => '1.1',
                    'headers' => [
                        'Authorization' => 'Basic ' . \base64_encode($this->consumer_key . ':' . $this->consumer_secret),
                    ],
                    'body' => $customer->get_billing(),
                ];
                $response = wp_remote_post($this->sync_endpoint, $args);

                $response_code = wp_remote_retrieve_response_code($response);

                $response_body = wp_remote_retrieve_body($response);

                if ($response_code >= 400 && ! empty($response_body)) {
                    throw new \Exception("order sync errore: '{$response_body}'");
                }
            }
        } catch (\Exception $exception) {
            \error_log($exception);
        }
    }
}
