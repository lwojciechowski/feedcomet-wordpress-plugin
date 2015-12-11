<?php

/**
 * Made with ❤ by vuefeed
 *
 * XML Feed generation
 */

class vue_api_client
{
	const BASE_DOMAIN = 		'http://localhost:8000/';
	const API_BASE_URL = 		self::BASE_DOMAIN . 'api/';
	const ACCESS_TOKEN_URL = 	self::BASE_DOMAIN . 'oauth/access_token';
	const PRODUCT_URL = 		self::API_BASE_URL . 'product';
	const GRANT_TYPE = 			'client_credentials';

	private $client_id;
	private $secret;

	/**
     * Create VUE Feed Api client
     *
     * @param string $id Oauth client id
     * @param string $secret Oauth client secret
     */
	public function __construct($client_id, $secret)
	{
		$this->client_id = $client_id;
		$this->secret = $secret;
	}

	/**
     * Get access token
     *
     * @return string Access Token for provided client
     */
	public function get_access_token($refresh = false)
	{
		$time = time();
		if(!$refresh) {
			$token_ttl = get_option('vue_feed_access_token_ttl', 0);
		}

		if(isset($token_ttl) && $time < $token_ttl) {
			return get_option('vue_feed_access_token');
		} else {
			$response = wp_remote_post(self::ACCESS_TOKEN_URL, array(
				'body' => array(
					'client_id' => $this->client_id, 
					'client_secret' => $this->secret, 
					'grant_type' => self::GRANT_TYPE
				),
			));

			if(!is_wp_error($response)) {
				$body = json_decode($response['body']);
				update_option('vue_feed_access_token', $body->access_token);
				update_option('vue_feed_access_token_ttl', $time + intval($body->expires_in));

				return $body->access_token;
			}	

			return false;
		}
	}

	/**
     * Send Product to the API
     *
     * @param Product $product product to submit
     * @return boolean Information about success of submit
     */
	public function add_product(vue_product $product)
	{
		$args = array(
			'method' => $product->get_remote_id() > 0 ? 'PUT' : 'POST',
			'headers' => array(
				'Authorization' => $this->get_access_token(),
			),
			'body' => $product->get_json(),
		);

		$response = wp_remote_post(
			$this->get_product_url($product->get_remote_id()),
			$args
		);
		$responseBody = json_decode($response['body']);

		if((int)$response['response']['code'] == 200) {
			$product->set_last_updated();
			$product->set_remote_id($responseBody->id);
			return true;
		} else {
			return false;
		}
	}

	private function get_product_url($id)
	{
		if($id > 0) {
			return self::PRODUCT_URL . '/' . $id;
		} else {
			return self::PRODUCT_URL;
		}

	}
}

if (!defined('ABSPATH')) exit;
