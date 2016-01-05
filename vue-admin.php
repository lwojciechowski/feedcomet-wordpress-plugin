<?php

/**
 * Made with ❤ by vuefeed
 *
 * Administration panel
 */

class vue_admin
{

    const OPTION_CLIENT_ID = 'vuefeed-client-id';
    const OPTION_CLIENT_SECRET = 'vuefeed-client-secret';

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
        if((defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) || wp_is_post_revision($id) || get_post_type($id) !== 'product') {
            return;
        }

        $client_id = get_option(self::OPTION_CLIENT_ID, false);
        $client_secret = get_option(self::OPTION_CLIENT_SECRET, false);

        if(!$client_id || !$client_secret) {
            return;
        }

        $client = new vue_api_client($client_id, $client_secret);

        $client->add_product(new vue_product(get_post($id)));
    }

    public function admin_menu()
    {
        add_submenu_page( 'woocommerce', 'VueFeed Options', 'VueFeed Options', 'manage_options', 'vuefeed-options', array($this, 'options_page'));
    }

    public function options_page()
    {
        if(!current_user_can('manage_options')) {
            exit('Access denied');
        }

        if(isset($_POST['client']) && isset($_POST['secret'])) {
            update_option(self::OPTION_CLIENT_ID, $_POST['client']);
            update_option(self::OPTION_CLIENT_SECRET, $_POST['secret']);
        }

        $client = get_option(self::OPTION_CLIENT_ID, '');

        include 'templates/options-page.php';
    }
}

if (!defined('ABSPATH')) exit;

global $vue_admin;
$vue_admin = new vue_admin();
