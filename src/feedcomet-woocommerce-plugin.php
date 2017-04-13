<?php
/*
Plugin Name: feedcomet WooCommerce plugin
Plugin URI: http://feedcomet.com/
Description: feedcomet integration plugin for WooCommerce
Version: 1.0.5
Author: feedcomet
Author URI: http://feedcomet.com/
License: GPL2
 */
if (!defined('ABSPATH')) {
    exit;
}

const UPDATE_EVENT_NAME = 'feedcomet_products_update';
define('FEEDCOMET_BASEFILE', __FILE__);

/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once('feedcomet-admin.php');
    require_once('feedcomet-api-client.php');
    require_once('feedcomet-product.php');


    // Add hourly update check to wp_cron
    if (!wp_next_scheduled(UPDATE_EVENT_NAME)) {
        wp_schedule_event(time(), 'hourly', UPDATE_EVENT_NAME);
    }

    add_action(UPDATE_EVENT_NAME, 'check_products_updates');

    function check_products_updates()
    {
        $client = feedcomet_api_client();
        $client->update_products();
    }

    // Admin
    if (is_admin()) {
        $feedcomet_admin = new feedcomet_admin();
        $feedcomet_admin->add_actions();
    }
}
