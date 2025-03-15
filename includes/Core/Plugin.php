<?php
namespace CoffeeWizard\Core;

class Plugin {
    private $admin;
    private $public;
    private $shortcode;

    public function init() {
        // Initialize admin
        $this->admin = new Admin\Admin();
        $this->admin->init();

        // Initialize public
        $this->public = new Frontend\Frontend();
        $this->public->init();

        // Register shortcode
        $this->shortcode = new Frontend\Shortcode();
        $this->shortcode->register();

        // Register AJAX handlers
        $this->register_ajax_handlers();
    }

    private function register_ajax_handlers() {
        // Admin AJAX handlers
        add_action('wp_ajax_save_coffee_categories', array($this->admin, 'save_coffee_categories'));
        add_action('wp_ajax_save_weight_coefficients', array($this->admin, 'save_weight_coefficients'));
        add_action('wp_ajax_save_grinder_options', array($this->admin, 'save_grinder_options'));

        // Public AJAX handlers
        add_action('wp_ajax_get_subcategories', array($this->public, 'get_subcategories'));
        add_action('wp_ajax_nopriv_get_subcategories', array($this->public, 'get_subcategories'));
        add_action('wp_ajax_get_products', array($this->public, 'get_products'));
        add_action('wp_ajax_nopriv_get_products', array($this->public, 'get_products'));
    }
} 