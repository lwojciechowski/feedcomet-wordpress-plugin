<?php

/**
 * Made with ❤ by feedcomet
 *
 * Administration panel
 */

class feedcomet_admin
{

    const OPTION_TOKEN = 'feedcomet_token';

    /**
     * Setup hooks
     */
    public function __construct()
    {
        // Product options
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    /**
     * Init all things required for admin
     */
    public function admin_init()
    {
        add_action('save_post', array($this, 'save_product'));
    }


    public function save_product($id)
    {
        if (
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            wp_is_post_revision($id) ||
            get_post_type($id) !== 'product'
        ) {
            return;
        }

        // $client_id = get_option(self::OPTION_CLIENT_ID, false);
        // $client_secret = get_option(self::OPTION_CLIENT_SECRET, false);

        // if (!$client_id || !$client_secret) {
        //     return;
        // }

        // $client = new vue_api_client($client_id, $client_secret);

        // $client->add_product(new vue_product(get_post($id)));
    }

    public function admin_menu()
    {
        add_submenu_page(
            'woocommerce',
            'FeedComet Options',
            'FeedComet Options',
            'manage_options',
            'feedcomet-options',
            array($this, 'options_page')
        );
    }

    public function options_page()
    {
        if (!current_user_can('manage_options')) {
            exit('Access denied');
        }

        if (isset($_POST['token'])) {
            update_option(self::OPTION_TOKEN, $_POST['token']);
        }

        $token = get_option(self::OPTION_TOKEN, '');

        if ($token) {
            $client = new feedcomet_api_client($token);
        }

        include 'templates/options-page.php';
    }
}

if (!defined('ABSPATH')) {
    exit;
}

global $feedcomet_admin;
$feedcomet_admin = new feedcomet_admin();
