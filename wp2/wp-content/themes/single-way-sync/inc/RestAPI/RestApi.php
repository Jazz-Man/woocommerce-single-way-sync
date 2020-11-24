<?php


namespace Sync\WpDestination\RestAPI;


use JazzMan\AutoloadInterface\AutoloadInterface;
use Sync\WpDestination\RestAPI\Controllers\SyncDataController;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Server;
use function implode;

class RestApi implements AutoloadInterface
{

	public function load()
	{
		add_action('rest_api_init',[$this,'create_rest_routes']);
	}

	public function create_rest_routes()
	{
		// Remove the default cors server headers.
		remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');

		// Adds new cors server headers.
		add_filter('rest_pre_serve_request', [$this, 'add_cors_support'], 0, 4);

		$sync = new SyncDataController();
		$sync->register_routes();
	}

	/**
	 * @param bool $served
	 * @param WP_HTTP_Response $result
	 * @param WP_REST_Request $request
	 * @param WP_REST_Server $server
	 * @return bool
	 */
	public function add_cors_support($served, $result, $request, $server)
	{
		$http_methods = [
			WP_REST_Server::READABLE,
			WP_REST_Server::CREATABLE,
			WP_REST_Server::DELETABLE,
			'OPTIONS',
		];

		$server->send_header('Access-Control-Allow-Methods', implode(',', $http_methods));
		$server->send_header('Access-Control-Allow-Origin', home_url());
		$server->send_header('Access-Control-Allow-Credentials', 'true');

		return $served;
	}
}
