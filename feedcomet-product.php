<?php

/**
 * Made with ❤ by vuefeed
 *
 * XML Item generation
 */

class feedcomet_product
{
	// Constraints
	const MAX_ID_LEN = 50;
	const MAX_TITLE_LEN = 150;
	const MAX_DESCRIPTION_LEN = 5000;
	const MAX_LINK_LEN = 2000;
	const MAX_PROD_TYPE_LEN = 750;
	const META_LAST_UPDATE = 'vuefeed-last-updated';
	const META_REMOTE_ID = 'vuefeed-remote-id';

	private $product;
	private $currency;
	private $settings;

	static $categories_cache = array();

	/**
	* Setup hooks
	*
	* @param $product Product as WooCommerce object
	*/
	public function __construct(WP_Post $product)
	{
		$this->product = new WC_Product($product);
	}

	/**
	* Represent product as Google XML
	*
	* @return string XML representation of product ready for Google Product Feed
	*/
	public function get_json()
	{
		$p = $this->product;
		$product_array = array(
			'id' => substr($p->id, 0, 50),
			'title' => substr($p->get_title(),0,150),
			'description' => substr($p->get_post_data()->post_excerpt,0,5000),
			'link' => substr($p->get_permalink(),0,2000),
			'image_link' => substr(wp_get_attachment_url($p->get_image_id()),0,2000),
			'condition' => get_option('tfm_shrike_setting_condition','new'),
			'availability' => $p->is_in_stock() ? 'in stock' : 'out of stock',
			'price' => sprintf('%s %s', $p->get_price(), get_woocommerce_currency()),
			'product_type' => substr($this->get_product_type($p),0,750),
		);

		return json_encode($product_array);
	}

	private function get_product_type($post)
	{
		$args = array( 'taxonomy' => 'product_cat',);

		$terms = wp_get_post_terms($post->id,'product_cat', $args);

		$result = array();

		if(count($terms) == 0) {
			return '';
		}

		$digest = function ($id) use (&$result, &$digest)
		{
			if(!isset(self::$categories_cache[$id])) {
				self::$categories_cache[$id] = get_term_by('id', $id, 'product_cat', 'ARRAY_A');
			}

			$term = self::$categories_cache[$id];

			if($term['parent']) {
				$digest($term['parent']);
			}

			$result[] = $term['name'];
			unset($term);
		};

		$digest($terms[0]->term_id);

		return implode(' &gt; ', $result);
	}

	public function get_last_updated()
	{
		return get_post_meta($this->product->id, self::META_LAST_UPDATE, true);
	}

	public function set_last_updated($time = 0)
	{
		if(!$time) {
			$time = time();
		}

		return update_post_meta($this->product->id, self::META_LAST_UPDATE, $time);
	}

	public function get_remote_id()
	{
		return get_post_meta($this->product->id, self::META_REMOTE_ID, true);
	}

	public function set_remote_id($id)
	{
		return update_post_meta($this->product->id, self::META_REMOTE_ID, $id);
	}
}
