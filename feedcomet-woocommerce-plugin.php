<?php
/*
Plugin Name: feedcomet WooCommerce plugin
Plugin URI: http://feedcomet.com/
Description: feedcomet integration plugin for WooCommerce
Version: 1.0.0
Author: feedcomet
Author URI: http://feedcomet.com/
License: GPL2
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once('feedcomet-product.php');
    require_once('feedcomet-api-client.php');

    // Admin
    if (is_admin()) {
        require_once('feedcomet-admin.php');
    }
}
