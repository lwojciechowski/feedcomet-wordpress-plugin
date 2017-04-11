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
        add_action('woocommerce_product_set_stock_status', array($this, 'change_stock_status'));

        add_action('delete_post', array($this, 'delete_product'));
        add_action('wp_trash_post', array($this, 'delete_product'));

        $client = new feedcomet_api_client();
        if($client->get_token() == '' && $_REQUEST['page'] !== 'feedcomet-options') {
            add_action('admin_notices', array($this, 'unconfigured_notice'));
        }

        // Settings link in the plugins list
        add_filter('plugin_action_links_' . plugin_basename(FEEDCOMET_BASEFILE), array($this, 'add_settings_link'));
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

    public function change_stock_status($id, $status)
    {
        if (wp_is_post_revision($id)) {
            return;
        }
        $client = new feedcomet_api_client();
        $client->update_product($id);
    }

    public function delete_product($id)
    {
        if (get_post_type($id) !== 'product') {
            return;
        }

        $client = new feedcomet_api_client();
        $client->delete_product($id);
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
        $token_error = false;

        if (isset($_POST['token'])) {
            $token_error = !$client->set_token($_POST['token']);
        }

        if (isset($_POST['disconnect'])) {
            $client->clear_token();
        }

        $client->update_products();

        // Store it for the template
        $token = $client->get_token();

        include 'templates/options-page.php';
    }

    public function ajax_script() {
        include 'templates/ajax.html';
    }

    public function unconfigured_notice()
    {
        include 'templates/unconfigured-notice.php';
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

    public function add_settings_link($links)
    {
        array_unshift(
            $links,
            sprintf(
                '<a href="%s">%s</a>',
                admin_url('admin.php?page=feedcomet-options'),
                __('Settings', 'feedcomet')
            )
        );
        return $links;
    }
}
