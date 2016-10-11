<?php

/**
 * Made with ❤ by vuefeed
 *
 * XML Feed generation
 */

class feedcomet_api_client
{
	const BASE_DOMAIN = 'http://172.19.0.1:8000/';
	const API_SOURCE_URL = self::BASE_DOMAIN . 'api/v1/sources/register';
	const OPTION_SOURCE = 'feedcomet_source';

	protected $token;
	protected $source_id;

	/**
     * Create FeedComet Api client
     *
     * @param string $token Authorization token
     */
	public function __construct($token)
	{
		$this->token = $token;
		$this->source_id = $this->get_source_id();
	}

	/**
     * Get access token
     *
     * @return string Access Token for provided client
     */
	protected function get_source_id()
	{
		$source_id = $token = get_option(self::OPTION_SOURCE, '');

		if (!$source_id) {
			$response = wp_remote_get(
				self::API_SOURCE_URL . '?eic=' . $this->plugin_id(),
				array(
					'headers' => array('PluginToken' => $this->token),
				)
			);
			if (!is_wp_error($response)) {
				update_option(self::OPTION_SOURCE, $response['body']);
				$source_id = $response['body'];
			}
		}

		return $source_id;
	}

	protected function plugin_id()
	{
		return md5('woocommerce' + home_url());
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
				'Authorization' => 'Bearer ' . $this->get_access_token(true),
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

	public function get_source_id()
	{
		return $this->source_id;
	}
}
