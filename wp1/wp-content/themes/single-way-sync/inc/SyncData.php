<?php

namespace Sync\WpSource;

use JazzMan\AutoloadInterface\AutoloadInterface;

class SyncData implements AutoloadInterface
{
    /**
     * @var string
     */
    private $cron_event = 'order_sync';

    public function load()
    {
//        add_action('woocommerce_payment_complete', [$this, 'add_order_sync_cron_job']);

        // just for debugging
        add_action('init', [$this, 'add_order_sync_cron_job']);


        add_action($this->cron_event, [$this, 'order_sync']);
    }

	/**
	 * Perhaps it will be the ID of a non-existent order.
	 * This ID is passed here only for testing
	 *
	 * @param int $order_id
	 */
	public function add_order_sync_cron_job($order_id = 14)
    {
//        if (! wp_next_scheduled($this->cron_event)) {
            wp_schedule_single_event(\time(), $this->cron_event, \compact('order_id'));
//        }
    }

    /**
     * @param int $order_id
     */
    public function order_sync($order_id = 14)
    {
    	if (empty($order_id)){
			$order_id = 14;
		}

		$order = wc_get_order($order_id);

		if ($order instanceof \WC_Abstract_Order){
			dump($order);
		}
    }
}
