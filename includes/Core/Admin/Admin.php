<?php
namespace CoffeeWizard\Core\Admin;

class Admin {
    public function init() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function add_menu_pages() {
        add_menu_page(
            __('Coffee Wizard', 'coffee-wizard'),
            __('Coffee Wizard', 'coffee-wizard'),
            'manage_options',
            'coffee-wizard',
            array($this, 'render_main_page'),
            'dashicons-coffee',
            30
        );

        add_submenu_page(
            'coffee-wizard',
            __('Categories', 'coffee-wizard'),
            __('Categories', 'coffee-wizard'),
            'manage_options',
            'coffee-wizard-categories',
            array($this, 'render_categories_page')
        );

        add_submenu_page(
            'coffee-wizard',
            __('Weights & Coefficients', 'coffee-wizard'),
            __('Weights & Coefficients', 'coffee-wizard'),
            'manage_options',
            'coffee-wizard-weights',
            array($this, 'render_weights_page')
        );

        add_submenu_page(
            'coffee-wizard',
            __('Grinder Options', 'coffee-wizard'),
            __('Grinder Options', 'coffee-wizard'),
            'manage_options',
            'coffee-wizard-grinders',
            array($this, 'render_grinders_page')
        );
    }

    public function enqueue_scripts($hook) {
        if (strpos($hook, 'coffee-wizard') === false) {
            return;
        }

        wp_enqueue_style(
            'coffee-wizard-admin',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            COFFEE_WIZARD_VERSION
        );

        wp_enqueue_script(
            'coffee-wizard-admin',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'jquery-ui-sortable'),
            COFFEE_WIZARD_VERSION,
            true
        );

        wp_localize_script('coffee-wizard-admin', 'coffeeWizardAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('coffee-wizard-admin-nonce'),
        ));
    }

    public function render_main_page() {
        include COFFEE_WIZARD_PLUGIN_DIR . 'templates/admin/main-page.php';
    }

    public function render_categories_page() {
        include COFFEE_WIZARD_PLUGIN_DIR . 'templates/admin/categories-page.php';
    }

    public function render_weights_page() {
        include COFFEE_WIZARD_PLUGIN_DIR . 'templates/admin/weights-page.php';
    }

    public function render_grinders_page() {
        include COFFEE_WIZARD_PLUGIN_DIR . 'templates/admin/grinders-page.php';
    }

    public function save_coffee_categories() {
        check_ajax_referer('coffee-wizard-admin-nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized access', 'coffee-wizard'));
        }

        $categories = isset($_POST['categories']) ? $_POST['categories'] : array();
        $sanitized_categories = array_map('sanitize_text_field', $categories);
        
        update_option('coffee_wizard_categories', $sanitized_categories);
        wp_send_json_success();
    }

    public function save_weight_coefficients() {
        check_ajax_referer('coffee-wizard-admin-nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized access', 'coffee-wizard'));
        }

        $weights = isset($_POST['weights']) ? $_POST['weights'] : array();
        $sanitized_weights = array();

        foreach ($weights as $weight) {
            $sanitized_weights[] = array(
                'weight' => intval($weight['weight']),
                'coefficient' => floatval($weight['coefficient'])
            );
        }

        update_option('coffee_wizard_weights', $sanitized_weights);
        wp_send_json_success();
    }

    public function save_grinder_options() {
        check_ajax_referer('coffee-wizard-admin-nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized access', 'coffee-wizard'));
        }

        $grinders = isset($_POST['grinders']) ? $_POST['grinders'] : array();
        $sanitized_grinders = array();

        foreach ($grinders as $grinder) {
            $sanitized_grinders[] = array(
                'name' => sanitize_text_field($grinder['name']),
                'price' => floatval($grinder['price'])
            );
        }

        update_option('coffee_wizard_grinders', $sanitized_grinders);
        wp_send_json_success();
    }
} 