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
    const META_LAST_UPDATE = 'feedcomet_last_updated';
    const META_SUCCESSFULLY_MODIFIED = 'feedcomet_successfully_modified';

    private $product;
    private $currency;
    private $settings;

    static $categories_cache = array();

    /**
    * Setup hooks
    *
    * @param $product Product as WooCommerce object
    */
    public function __construct($product)
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
            'id' => (string)$p->id,
            'title' => $p->get_title(),
            'description' => $p->get_post_data()->post_excerpt,
            'link' => $p->get_permalink(),
            'image' => (string)wp_get_attachment_url($p->get_image_id()),
            'availability' => $p->is_in_stock() ? 'in stock' : 'out of stock',
            'price' => sprintf('%s %s', $p->get_price(), get_woocommerce_currency()),
            'category' => $this->get_product_type($p),
            'attrs' => [
                'short_description' => $p->get_post_data()->post_excerpt,
                'sku' => $p->get_sku(),
            ]
        );

        foreach ($p->get_attributes() as $attr) {
            $attr_value = $p->get_attribute($attr['name']);

            if($attr_value) {
                $product_array['attrs'][$attr['name']] = $attr_value;
            }
        }

        return json_encode($product_array);
    }

    private function get_product_type($post)
    {
        $args = array( 'taxonomy' => 'product_cat',);

        $terms = wp_get_post_terms($post->id, 'product_cat', $args);

        $result = array();

        if (count($terms) == 0) {
            return '';
        }

        $digest = function ($id) use (&$result, &$digest) {
            if (!isset(self::$categories_cache[$id])) {
                self::$categories_cache[$id] = get_term_by('id', $id, 'product_cat', 'ARRAY_A');
            }

            $term = self::$categories_cache[$id];

            if ($term['parent']) {
                $digest($term['parent']);
            }

            $result[] = $term['name'];
            unset($term);
        };

        $digest($terms[0]->term_id);

        return implode(' &gt; ', $result);
    }

    public function set_last_updated($time = 0)
    {
        if (!$time) {
            $time = time();
        }

        return update_post_meta($this->product->id, self::META_LAST_UPDATE, $time);
    }

    public function set_successfully_modified($status) {
        return update_post_meta($this->product->id, self::META_SUCCESSFULLY_MODIFIED, $status);
    }

    public function get_id()
    {
        return $this->product->id;
    }
}
