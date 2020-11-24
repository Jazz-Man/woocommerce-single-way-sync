<?php

namespace Sync\WpDestination\RestAPI\Controllers;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class SyncDataController extends WP_REST_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'sync';
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'order';
    /**
     * @var \stdClass|null
     */
    private $admin_user;

    private $customer_data = [];

    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'create_item'],
                'permission_callback' => [$this, 'create_item_permissions_check'],
                'args' => $this->get_collection_params(),
            ],
            'schema' => [$this, 'get_public_item_schema'],
        ]);
    }

    /**
     * @return array|array[]
     */
    public function get_collection_params()
    {
        $params = [
            'email' => [
                'description' => __('Customer Email'),
            ],
            'first_name' => [
                'description' => __('Customer First Name'),
            ],
            'last_name' => [
                'description' => __('Customer Last Name'),
            ],
            'company' => [
                'description' => __('Customer Company'),
            ],
            'address_1' => [
                'description' => __('Customer Address 1'),
            ],
            'address_2' => [
                'description' => __('Customer Address 2'),
            ],
            'city' => [
                'description' => __('Customer City'),
            ],
            'postcode' => [
                'description' => __('Customer Postcode'),
            ],
            'country' => [
                'description' => __('Customer Country'),
            ],
            'state' => [
                'description' => __('Customer State'),
            ],
            'phone' => [
                'description' => __('Customer Phone'),
            ],
        ];

        foreach ($params as $key => $param) {
            $param['validate_callback'] = 'rest_validate_request_arg';
            $param['sanitize_callback'] = [$this, 'sanitize_request_arg'];
            if ($key === 'email') {
                $param['format'] = 'email';
            }
            $param['type'] = 'string';
            $param['required'] = $key === 'email';

            $params[$key] = $param;
        }

        return $params;
    }

    /**
     * @param mixed $value
     * @param WP_REST_Request $request
     * @param string $param
     *
     * @return mixed
     */
    public function sanitize_request_arg($value, $request, $param)
    {
        $value = rest_sanitize_request_arg($value, $request, $param);

        if (is_wp_error($value)) {
            return $value;
        }

        if (! empty($value)) {
            $this->customer_data[$param] = $value;
        }

        return $value;
    }

    /**
     * @param WP_REST_Request $request
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
        $consumer_key = ! empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : false;
        $consumer_secret = ! empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : false;

        // Stop if don't have any key.
        if (! $consumer_key || ! $consumer_secret) {
            return $this->not_allowed();
        }

        $this->admin_user = $this->get_user_data_by_consumer_key($consumer_key);

        if (empty($this->admin_user)) {
            return $this->not_allowed();
        }

        if (! \hash_equals($this->admin_user->consumer_secret, $consumer_secret)) {
            return $this->not_allowed();
        }

        return true;
    }

    /**
     * @return WP_Error
     */
    private function not_allowed()
    {
        return new WP_Error(
            'rest_not_allowed',
            __('Sorry, you are not allowed to create, update, delete or edite posts'),
            ['status' => rest_authorization_required_code()]
        );
    }

    /**
     * @param string $consumer_key
     * @return \stdClass|null
     */
    private function get_user_data_by_consumer_key(string $consumer_key)
    {
        global $wpdb;

        $consumer_key = wc_api_hash(sanitize_text_field($consumer_key));

        return $wpdb->get_row(
            $wpdb->prepare(
                "
			SELECT key_id, user_id, permissions, consumer_key, consumer_secret, nonces
			FROM {$wpdb->prefix}woocommerce_api_keys
			WHERE consumer_key = %s
		",
                $consumer_key
            )
        );
    }

    public function create_item($request)
    {
        $customer_id = email_exists($this->customer_data['email']);

        try {
            $customer = new \WC_Customer((int) $customer_id);

            foreach ($this->customer_data as $prop => $value) {
                $billing = "billing_{$prop}";
                $shipping = "shipping_{$prop}";

                $customer->{$billing} = $value;
                $customer->{$shipping} = $value;
            }

            $customer->save();

            $product_id = wc_get_product_id_by_sku('ASSIGN_ME');

            if (! empty($product_id)) {
                $product = wc_get_product($product_id);
                $order = wc_create_order([
                    'customer_id' => $customer->get_id(),
                    'status' => 'completed',
                ]);

                $order->set_address($customer->get_billing());

                $order->add_product($product);

                $order->save();
            }

            wp_send_json_success($order->get_id());
        } catch (\Exception $exception) {
            wp_send_json_error($exception->getMessage());
            \error_log($exception);
        }
    }
}
