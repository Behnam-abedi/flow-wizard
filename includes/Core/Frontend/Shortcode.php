<?php
namespace CoffeeWizard\Core\Frontend;

class Shortcode {
    public function register() {
        add_shortcode('coffee_wizard', array($this, 'render_wizard'));
    }

    public function render_wizard($atts) {
        wp_enqueue_style(
            'coffee-wizard-public',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/css/public.css',
            array(),
            COFFEE_WIZARD_VERSION
        );

        wp_enqueue_script(
            'coffee-wizard-public',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/js/public.js',
            array('jquery'),
            COFFEE_WIZARD_VERSION,
            true
        );

        wp_localize_script('coffee-wizard-public', 'coffeeWizardPublic', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('coffee-wizard-public-nonce'),
            'currency' => get_woocommerce_currency_symbol(),
        ));

        ob_start();
        include COFFEE_WIZARD_PLUGIN_DIR . 'templates/frontend/wizard-form.php';
        return ob_get_clean();
    }
} 