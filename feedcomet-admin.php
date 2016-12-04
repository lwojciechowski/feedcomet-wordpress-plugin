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
    public function add_actions()
    {
        // Product options
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('save_post', array($this, 'save_product'));
        add_action('admin_footer', array($this, 'ajax_script'));
        add_action('wp_ajax_products_sync', array($this, 'ajax_products_sync'));
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

        $client = new feedcomet_api_client();
        $client->update_product($id);
    }

    public function admin_menu()
    {
        add_submenu_page(
            'woocommerce',
            'feedcomet options',
            'feedcomet options',
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

        // Store it for the template
        $token = $client->get_token();

        include 'templates/options-page.php';
    }

    public function ajax_script() {
        include 'templates/ajax.html';
    }

    public function ajax_products_sync() {
        $client = new feedcomet_api_client();
        if ($client->update_products()) {
            echo '1';
        } else {
            echo '0';
        }
        wp_die();
    }
}
