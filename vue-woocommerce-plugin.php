<?php
/*
Plugin Name: vuefeed WordPress plugin
Plugin URI: http://vuefeed.com/
Description: vuefeed integration plugin for WooCommerce
Version: 1.0.0
Author: Łukasz Wojciechowski
Author URI: http://vuefeed.com/
License: GPL2
 */
if (!defined('ABSPATH')) exit;

/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	require_once('vue-product.php');
	require_once('vue-api-client.php');

    // Admin
    if(is_admin()) {
        require_once('vue-admin.php');
    }
}
