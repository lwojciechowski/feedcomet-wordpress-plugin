<?php

/**
 * Made with ❤ by feedcomet
 *
 * Administration panel
 */

class feedcomet_admin
{
    /**
     * Setup hooks
     */
    public function __construct()
    {
        // Product options
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('save_post', array($this, 'save_product'));
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
        ) { return; }

        $client = new feedcomet_api_client();
        $client->update_product($id);
    }

    public function admin_menu()
    {
        add_submenu_page(
            'woocommerce',
            'Feedcomet Options',
            'Feedcomet Options',
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

        $client = new feedcomet_api_client();

        if (isset($_POST['token'])) {
            $client->set_token($_POST['token']);
        }

        $client->update_products();
        $token = $client->get_token();

        include 'templates/options-page.php';
    }
}

if (!defined('ABSPATH')) {
    exit;
}

global $feedcomet_admin;
$feedcomet_admin = new feedcomet_admin();
